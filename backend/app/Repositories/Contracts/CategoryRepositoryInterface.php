<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find category by ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function find(int $id): ?Category;

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category;

    /**
     * Update a category.
     *
     * @param int $id
     * @param array $data
     * @return Category
     */
    public function update(int $id, array $data): Category;

    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
