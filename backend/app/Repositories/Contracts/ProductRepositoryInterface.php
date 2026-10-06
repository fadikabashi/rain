<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Get all products.
     *
     * @param array $relations
     * @return Collection
     */
    public function all(array $relations = []): Collection;

    /**
     * Get paginated products.
     *
     * @param int $perPage
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator;

    /**
     * Find product by ID.
     *
     * @param int $id
     * @param array $relations
     * @return Product|null
     */
    public function find(int $id, array $relations = []): ?Product;

    /**
     * Find product by ID or fail.
     *
     * @param int $id
     * @param array $relations
     * @return Product
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id, array $relations = []): Product;

    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product;

    /**
     * Update a product.
     *
     * @param int $id
     * @param array $data
     * @return Product
     */
    public function update(int $id, array $data): Product;

    /**
     * Delete a product.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get available products.
     *
     * @param array $relations
     * @return Collection
     */
    public function getAvailableProducts(array $relations = []): Collection;

    /**
     * Get products by category.
     *
     * @param int $categoryId
     * @param array $relations
     * @return Collection
     */
    public function getByCategory(int $categoryId, array $relations = []): Collection;

    /**
     * Get products with low stock.
     *
     * @param int $threshold
     * @return Collection
     */
    public function getLowStockProducts(int $threshold = 10): Collection;

    /**
     * Check if product has sufficient stock.
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function hasSufficientStock(int $productId, int $quantity): bool;

    /**
     * Update product stock.
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function updateStock(int $productId, int $quantity): bool;

    /**
     * Search products by query string with advanced filtering.
     *
     * @param string $query
     * @param array $filters ['category_id', 'manufacturer_id', 'type_id', 'min_price', 'max_price', 'availability', 'sort']
     * @param array $relations
     * @return Collection
     */
    public function search(string $query, array $filters = [], array $relations = []): Collection;
}
