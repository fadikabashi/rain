<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get all products.
     *
     * @param array $relations
     * @return Collection
     */
    public function all(array $relations = []): Collection
    {
        $query = Product::query();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }

    /**
     * Get paginated products.
     *
     * @param int $perPage
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        $query = Product::query();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->orderBy('id', 'DESC')->paginate($perPage);
    }

    /**
     * Find product by ID.
     *
     * @param int $id
     * @param array $relations
     * @return Product|null
     */
    public function find(int $id, array $relations = []): ?Product
    {
        $query = Product::query();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->find($id);
    }

    /**
     * Find product by ID or fail.
     *
     * @param int $id
     * @param array $relations
     * @return Product
     */
    public function findOrFail(int $id, array $relations = []): Product
    {
        $query = Product::query();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->findOrFail($id);
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update a product.
     *
     * @param int $id
     * @param array $data
     * @return Product
     */
    public function update(int $id, array $data): Product
    {
        $product = $this->findOrFail($id);
        $product->update($data);
        return $product->fresh();
    }

    /**
     * Delete a product.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $product = $this->findOrFail($id);
        return $product->delete();
    }

    /**
     * Get available products.
     *
     * @param array $relations
     * @return Collection
     */
    public function getAvailableProducts(array $relations = []): Collection
    {
        $query = Product::where('is_available', true)
            ->where('quantity', '>', 0);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }

    /**
     * Get products by category.
     *
     * @param int $categoryId
     * @param array $relations
     * @return Collection
     */
    public function getByCategory(int $categoryId, array $relations = []): Collection
    {
        $query = Product::where('category_id', $categoryId);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }

    /**
     * Get products with low stock.
     *
     * @param int $threshold
     * @return Collection
     */
    public function getLowStockProducts(int $threshold = \App\Constants\StockThreshold::LOW_STOCK_THRESHOLD): Collection
    {
        return Product::where('quantity', '<=', $threshold)
            ->where('is_available', true)
            ->get();
    }

    /**
     * Check if product has sufficient stock.
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function hasSufficientStock(int $productId, int $quantity): bool
    {
        $product = $this->find($productId);
        
        if (!$product || !$product->is_available) {
            return false;
        }
        
        return $product->quantity >= $quantity;
    }

    /**
     * Update product stock.
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function updateStock(int $productId, int $quantity): bool
    {
        $product = $this->findOrFail($productId);
        
        $product->quantity = max(0, $product->quantity + $quantity);
        
        if ($product->quantity <= 0) {
            $product->is_available = false;
        }
        
        return $product->save();
    }

    /**
     * Search products by query string with advanced filtering.
     *
     * @param string $query
     * @param array $filters ['category_id', 'manufacturer_id', 'type_id', 'min_price', 'max_price', 'availability', 'sort']
     * @param array $relations
     * @return Collection
     */
    public function search(string $query, array $filters = [], array $relations = []): Collection
    {
        $searchQuery = Product::query();
        
        // Load relations if provided
        if (!empty($relations)) {
            $searchQuery->with($relations);
        }
        
        // Search in both English and Arabic names and descriptions
        if (!empty(trim($query))) {
            $searchQuery->where(function ($q) use ($query) {
                $q->where('name_en', 'LIKE', "%{$query}%")
                  ->orWhere('name_ar', 'LIKE', "%{$query}%")
                  ->orWhere('description_en', 'LIKE', "%{$query}%")
                  ->orWhere('description_ar', 'LIKE', "%{$query}%");
            });
        }
        
        // Filter by category
        if (!empty($filters['category_id'])) {
            $searchQuery->where('category_id', $filters['category_id']);
        }
        
        // Filter by manufacturer
        if (!empty($filters['manufacturer_id'])) {
            $searchQuery->where('manfacturer_id', $filters['manufacturer_id']);
        }
        
        // Filter by type
        if (!empty($filters['type_id'])) {
            $searchQuery->where('type_id', $filters['type_id']);
        }
        
        // Filter by price range
        if (!empty($filters['min_price'])) {
            $searchQuery->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $searchQuery->where('price', '<=', $filters['max_price']);
        }
        
        // Filter by availability
        if (isset($filters['availability'])) {
            if ($filters['availability'] === 'in_stock') {
                $searchQuery->where('is_available', true)->where('quantity', '>', 0);
            } elseif ($filters['availability'] === 'out_of_stock') {
                $searchQuery->where(function ($q) {
                    $q->where('is_available', false)->orWhere('quantity', '<=', 0);
                });
            } elseif ($filters['availability'] === 'low_stock') {
                $searchQuery->where('is_available', true)
                    ->where('quantity', '>', 0)
                    ->where('quantity', '<=', 10);
            }
        } else {
            // Default: only show available products if no availability filter is set
            $searchQuery->where('is_available', true);
        }
        
        // Apply sorting
        $sortBy = $filters['sort'] ?? 'newest';
        switch ($sortBy) {
            case 'price_low_high':
                $searchQuery->orderBy('price', 'ASC');
                break;
            case 'price_high_low':
                $searchQuery->orderBy('price', 'DESC');
                break;
            case 'name_asc':
                $searchQuery->orderBy('name_en', 'ASC');
                break;
            case 'name_desc':
                $searchQuery->orderBy('name_en', 'DESC');
                break;
            case 'discount':
                $searchQuery->orderBy('discount', 'DESC');
                break;
            case 'newest':
            default:
                $searchQuery->orderBy('id', 'DESC');
                break;
        }
        
        return $searchQuery->get();
    }
}
