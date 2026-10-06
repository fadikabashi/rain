<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Get all orders.
     *
     * @param array $relations
     * @return Collection
     */
    public function all(array $relations = []): Collection
    {
        $query = Order::query();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->orderBy('id', 'DESC')->get();
    }

    /**
     * Get paginated orders.
     *
     * @param int $perPage
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        $query = Order::query();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->orderBy('id', 'DESC')->paginate($perPage);
    }

    /**
     * Find order by ID.
     *
     * @param int $id
     * @param array $relations
     * @return Order|null
     */
    public function find(int $id, array $relations = []): ?Order
    {
        $query = Order::query();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->find($id);
    }

    /**
     * Find order by ID or fail.
     *
     * @param int $id
     * @param array $relations
     * @return Order
     */
    public function findOrFail(int $id, array $relations = []): Order
    {
        $query = Order::query();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->findOrFail($id);
    }

    /**
     * Create a new order.
     *
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * Update an order.
     *
     * @param int $id
     * @param array $data
     * @return Order
     */
    public function update(int $id, array $data): Order
    {
        $order = $this->findOrFail($id);
        $order->update($data);
        return $order->fresh();
    }

    /**
     * Delete an order.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $order = $this->findOrFail($id);
        // Remove line items first — FK on orderitems.order_id does not cascade.
        $order->orderitems()->delete();

        return $order->delete();
    }

    /**
     * Get orders by status.
     *
     * @param int $status
     * @param array $relations
     * @return Collection
     */
    public function getByStatus(int $status, array $relations = []): Collection
    {
        $query = Order::where('order_status', $status);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->orderBy('id', 'DESC')->get();
    }

    /**
     * Get order statistics.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'total' => Order::count(),
            'new' => Order::where('order_status', 0)->count(),
            'received' => Order::where('order_status', 1)->count(),
            'delivered' => Order::where('order_status', 2)->count(),
        ];
    }

    /**
     * Update order status.
     *
     * @param int $orderId
     * @param int $status
     * @return bool
     */
    public function updateStatus(int $orderId, int $status): bool
    {
        $order = $this->findOrFail($orderId);
        $order->order_status = $status;
        return $order->save();
    }
}
