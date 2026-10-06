<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Category::all();
    }

    /**
     * Find category by ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update a category.
     *
     * @param int $id
     * @param array $data
     * @return Category
     */
    public function update(int $id, array $data): Category
    {
        $category = $this->find($id);
        if (!$category) {
            throw new \Exception("Category not found");
        }
        $category->update($data);
        return $category->fresh();
    }

    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $category = $this->find($id);
        if (!$category) {
            return false;
        }
        return $category->delete();
    }
}
