<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    /**
     * Get all orders.
     *
     * @param array $relations
     * @return Collection
     */
    public function all(array $relations = []): Collection;

    /**
     * Get paginated orders.
     *
     * @param int $perPage
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator;

    /**
     * Find order by ID.
     *
     * @param int $id
     * @param array $relations
     * @return Order|null
     */
    public function find(int $id, array $relations = []): ?Order;

    /**
     * Find order by ID or fail.
     *
     * @param int $id
     * @param array $relations
     * @return Order
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id, array $relations = []): Order;

    /**
     * Create a new order.
     *
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order;

    /**
     * Update an order.
     *
     * @param int $id
     * @param array $data
     * @return Order
     */
    public function update(int $id, array $data): Order;

    /**
     * Delete an order.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get orders by status.
     *
     * @param int $status
     * @param array $relations
     * @return Collection
     */
    public function getByStatus(int $status, array $relations = []): Collection;

    /**
     * Get order statistics.
     *
     * @return array
     */
    public function getStatistics(): array;

    /**
     * Update order status.
     *
     * @param int $orderId
     * @param int $status
     * @return bool
     */
    public function updateStatus(int $orderId, int $status): bool;
}
