<?php

namespace App\Services;

use App\Constants\StockThreshold;
use App\DTOs\ProductDTO;
use App\Events\ProductCreated;
use App\Exceptions\ProductNotFoundException;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Helpers\CacheHelper;
use App\Services\Helpers\PhotoHandler;
use App\Services\LoggingService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected LoggingService $loggingService
    ) {}

    /**
     * Get all products.
     *
     * @param array $relations
     * @return Collection
     */
    public function getAll(array $relations = ['category', 'type', 'manfacturer']): Collection
    {
        return $this->productRepository->all($relations);
    }

    /**
     * Get new arrival products (latest products).
     *
     * @param int $limit
     * @param array $relations
     * @return Collection
     */
    public function getNewArrivals(int $limit = 7, array $relations = ['category', 'type', 'manfacturer']): Collection
    {
        return $this->productRepository->all($relations)
            ->sortByDesc('id')
            ->take($limit);
    }

    /**
     * Get best products (by discount).
     *
     * @param int $limit
     * @param array $relations
     * @return Collection
     */
    public function getBestProducts(int $limit = 7, array $relations = ['category', 'type', 'manfacturer']): Collection
    {
        return $this->productRepository->all($relations)
            ->sortByDesc('discount')
            ->take($limit);
    }

    /**
     * Get available products.
     *
     * @param array $relations
     * @return Collection
     */
    public function getAvailableProducts(array $relations = ['category', 'type', 'manfacturer']): Collection
    {
        return $this->productRepository->getAvailableProducts($relations);
    }

    /**
     * Get product by ID.
     *
     * @param int $id
     * @param array $relations
     * @return \App\Models\Product
     * @throws ProductNotFoundException
     */
    public function getById(int $id, array $relations = ['category', 'type', 'manfacturer'])
    {
        $product = $this->productRepository->find($id, $relations);

        if (!$product) {
            throw new ProductNotFoundException("Product with ID {$id} not found.");
        }

        return $product;
    }

    /**
     * Get related products (same category, excluding current product).
     *
     * @param int $productId
     * @param int|null $categoryId
     * @param int $limit
     * @return Collection
     */
    public function getRelatedProducts(int $productId, ?int $categoryId = null, int $limit = 4): Collection
    {
        $query = $this->productRepository->all(['category', 'type', 'manfacturer'])
            ->where('id', '!=', $productId)
            ->where('is_available', true);

        if ($categoryId) {
            $query = $query->where('category_id', $categoryId);
        }

        return $query->shuffle()->take($limit);
    }

    /**
     * Create a new product.
     *
     * @param ProductDTO $dto
     * @param UploadedFile|null $photo
     * @return \App\Models\Product
     */
    public function create(ProductDTO $dto, ?UploadedFile $photo = null)
    {
        $data = $dto->toArray();

        // Handle photo upload using helper
        $photoPath = PhotoHandler::store($photo);
        if ($photoPath) {
            $data['photo'] = $photoPath;
        }

        $product = $this->productRepository->create($data);

        // Clear product-related caches
        CacheHelper::clearProductCaches();

        // Log product creation
        $this->loggingService->logProduct('info', 'Product created', [
            'product_id' => $product->id,
            'product_name' => $product->name_en,
            'price' => $product->price,
            'quantity' => $product->quantity,
        ]);

        // Dispatch event for new product creation
        event(new ProductCreated($product));

        return $product;
    }

    /**
     * Update a product.
     *
     * @param int $id
     * @param ProductDTO $dto
     * @param UploadedFile|null $photo
     * @return \App\Models\Product
     * @throws ProductNotFoundException
     */
    public function update(int $id, ProductDTO $dto, ?UploadedFile $photo = null)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new ProductNotFoundException("Product with ID {$id} not found.");
        }

        $data = $dto->toArray();

        // Handle photo upload: replace old photo if new one provided, otherwise keep existing
        if ($photo) {
            $newPhotoPath = PhotoHandler::replace($product->photo, $photo);
            if ($newPhotoPath) {
                $data['photo'] = $newPhotoPath;
            }
        } else {
            // Keep existing photo by removing it from update data
            unset($data['photo']);
        }

        $updatedProduct = $this->productRepository->update($id, $data);

        // Clear product-related caches after update
        CacheHelper::clearProductCaches();

        // Log product update
        $this->loggingService->logProduct('info', 'Product updated', [
            'product_id' => $id,
            'product_name' => $updatedProduct->name_en,
            'changes' => array_keys($data),
        ]);

        return $updatedProduct;
    }

    /**
     * Delete a product.
     *
     * @param int $id
     * @return bool
     * @throws ProductNotFoundException
     * @throws \Exception If product has order items
     */
    public function delete(int $id): bool
    {
        $product = $this->productRepository->find($id, ['orderitems', 'images', 'documents']);

        if (!$product) {
            throw new ProductNotFoundException("Product with ID {$id} not found.");
        }

        // Check if product has order items - prevent deletion if it does
        if ($product->orderitems && $product->orderitems->count() > 0) {
            throw new \Exception("Cannot delete product that has been ordered. Product has {$product->orderitems->count()} order item(s).");
        }

        $productName = $product->name_en;

        // Delete all product images and their files
        if ($product->images) {
            foreach ($product->images as $image) {
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
        }

        // Delete all product documents and their files
        if ($product->documents) {
            foreach ($product->documents as $document) {
                if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
            }
        }

        // Delete main product photo
        PhotoHandler::delete($product->photo);

        // Delete the product (cascade will handle images, specifications, videos, documents)
        $deleted = $this->productRepository->delete($id);

        // Clear product-related caches after deletion
        if ($deleted) {
            CacheHelper::clearProductCaches();

            // Log product deletion
            $this->loggingService->logProduct('warning', 'Product deleted', [
                'product_id' => $id,
                'product_name' => $productName,
            ]);
        }

        return $deleted;
    }

    /**
     * Check if product has sufficient stock.
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function checkStock(int $productId, int $quantity): bool
    {
        return $this->productRepository->hasSufficientStock($productId, $quantity);
    }

    /**
     * Get products with low stock.
     *
     * @param int $threshold
     * @return Collection
     */
    public function getLowStockProducts(int $threshold = StockThreshold::LOW_STOCK_THRESHOLD): Collection
    {
        return $this->productRepository->getLowStockProducts($threshold);
    }

    /**
     * Get products by category.
     *
     * @param int $categoryId
     * @param array $relations
     * @return Collection
     */
    public function getByCategory(int $categoryId, array $relations = ['category', 'type', 'manfacturer']): Collection
    {
        return $this->productRepository->getByCategory($categoryId, $relations);
    }

    /**
     * Get paginated products.
     *
     * @param int $perPage
     * @param array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 15, array $relations = ['category', 'type', 'manfacturer'])
    {
        return $this->productRepository->paginate($perPage, $relations);
    }

    /**
     * Search products by query string with advanced filtering.
     *
     * @param string $query
     * @param array $filters ['category_id', 'manufacturer_id', 'type_id', 'min_price', 'max_price', 'availability', 'sort']
     * @param array $relations
     * @return Collection
     */
    public function search(string $query, array $filters = [], array $relations = ['category', 'type', 'manfacturer']): Collection
    {
        // Normalize filters
        $normalizedFilters = [];
        
        if (!empty($filters['category_id']) && $filters['category_id'] !== '0') {
            $normalizedFilters['category_id'] = (int) $filters['category_id'];
        }
        
        if (!empty($filters['manufacturer_id']) && $filters['manufacturer_id'] !== '0') {
            $normalizedFilters['manufacturer_id'] = (int) $filters['manufacturer_id'];
        }
        
        if (!empty($filters['type_id']) && $filters['type_id'] !== '0') {
            $normalizedFilters['type_id'] = (int) $filters['type_id'];
        }
        
        if (!empty($filters['min_price'])) {
            $normalizedFilters['min_price'] = (float) $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $normalizedFilters['max_price'] = (float) $filters['max_price'];
        }
        
        if (!empty($filters['availability'])) {
            $normalizedFilters['availability'] = $filters['availability'];
        }
        
        if (!empty($filters['sort'])) {
            $normalizedFilters['sort'] = $filters['sort'];
        }

        $results = $this->productRepository->search(trim($query), $normalizedFilters, $relations);

        // Log search activity
        $this->loggingService->logProduct('info', 'Product search performed', [
            'query' => $query,
            'filters' => $normalizedFilters,
            'results_count' => $results->count(),
        ]);

        return $results;
    }
}
