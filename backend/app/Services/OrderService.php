<?php

namespace App\Services;

use App\Constants\OrderStatus;
use App\DTOs\OrderDTO;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Models\Orderitem;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\LoggingService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected ProductRepositoryInterface $productRepository,
        protected InventoryService $inventoryService,
        protected LoggingService $loggingService
    ) {}

    /**
     * Get all orders.
     *
     * @param array $relations
     * @return Collection
     */
    public function getAll(array $relations = ['orderitems', 'coupon']): Collection
    {
        return $this->orderRepository->all($relations);
    }

    /**
     * Get paginated orders.
     *
     * @param int $perPage
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 15, array $relations = ['orderitems', 'coupon']): LengthAwarePaginator
    {
        return $this->orderRepository->paginate($perPage, $relations);
    }

    /**
     * Get order by ID.
     *
     * @param int $id
     * @param array $relations
     * @return Order
     */
    public function getById(int $id, array $relations = ['orderitems.product', 'coupon']): Order
    {
        return $this->orderRepository->findOrFail($id, $relations);
    }

    /**
     * Create a new order from cart items.
     *
     * @param OrderDTO $dto
     * @param array $cartItems
     * @return Order
     * @throws \Exception
     */
    public function createOrder(OrderDTO $dto, array $cartItems): Order
    {
        // Validate cart is not empty
        $this->validateCartNotEmpty($cartItems);

        // Validate stock availability for all items
        $this->validateStockAvailability($cartItems);

        // Create order within transaction
        $startTime = microtime(true);
        
        try {
            $order = DB::transaction(function () use ($dto, $cartItems) {
                // Create order record
                $order = $this->orderRepository->create($dto->toArray());

                // Process each cart item: create order item and reserve stock
                $this->processOrderItems($order, $cartItems);

                // Load relationships for return
                $order->load(['orderitems.product', 'coupon']);

                // Dispatch event for order creation
                event(new OrderCreated($order));

                return $order;
            });

            // Log successful order creation
            $duration = microtime(true) - $startTime;
            $this->loggingService->logOrder('info', 'Order created successfully', [
                'order_id' => $order->id,
                'total' => $order->total,
                'items_count' => count($cartItems),
                'duration_seconds' => round($duration, 4),
            ]);

            return $order;
        } catch (\Exception $e) {
            // Log order creation failure
            $duration = microtime(true) - $startTime;
            $this->loggingService->logOrder('error', 'Order creation failed', [
                'error' => $e->getMessage(),
                'dto' => $dto->toArray(),
                'cart_items_count' => count($cartItems),
                'duration_seconds' => round($duration, 4),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Validate that cart is not empty.
     *
     * @param array $cartItems
     * @return void
     * @throws \Exception
     */
    protected function validateCartNotEmpty(array $cartItems): void
    {
        if (empty($cartItems)) {
            throw new \Exception('Cannot create order with empty cart.');
        }
    }

    /**
     * Validate stock availability for all cart items.
     *
     * @param array $cartItems
     * @return void
     * @throws \Exception
     */
    protected function validateStockAvailability(array $cartItems): void
    {
        // Transform cart items to stock check format
        $itemsToCheck = $this->transformCartItemsForStockCheck($cartItems);

        // Check availability for all items
        $availabilityCheck = $this->inventoryService->checkMultipleAvailability($itemsToCheck);
        
        if (!$availabilityCheck['available']) {
            // Collect all error messages
            $errors = collect($availabilityCheck['errors'])
                ->pluck('message')
                ->implode(', ');
            throw new \Exception('Stock validation failed: ' . $errors);
        }
    }

    /**
     * Transform cart items array to stock check format.
     *
     * @param array $cartItems
     * @return array
     */
    protected function transformCartItemsForStockCheck(array $cartItems): array
    {
        return array_map(function ($item) {
            return [
                'product_id' => (int) $item['id'],
                'quantity' => (int) $item['quantity'],
            ];
        }, $cartItems);
    }

    /**
     * Process order items: create order items and reserve stock.
     *
     * @param Order $order
     * @param array $cartItems
     * @return void
     */
    protected function processOrderItems(Order $order, array $cartItems): void
    {
        foreach ($cartItems as $cartItem) {
            $productId = (int) $cartItem['id'];
            $quantity = (int) $cartItem['quantity'];

            // Create order item record
            Orderitem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);

            // Reserve stock for this product
            $this->inventoryService->reserveStock($productId, $quantity);
        }
    }

    /**
     * Update order status.
     *
     * @param int $orderId
     * @param int $status
     * @return Order
     */
    public function updateOrderStatus(int $orderId, int $status): Order
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $oldStatus = $order->order_status;
        
        $this->orderRepository->updateStatus($orderId, $status);
        
        $order->refresh();
        
        // Dispatch event if status changed
        if ($oldStatus !== $status) {
            event(new OrderStatusChanged($order, $oldStatus, $status));
            
            // Log status change
            $this->loggingService->logOrder('info', 'Order status updated', [
                'order_id' => $orderId,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'status_label' => \App\Constants\OrderStatus::label($status),
            ]);
        }
        
        return $order;
    }

    /**
     * Update an order.
     *
     * @param int $id
     * @param OrderDTO $dto
     * @return Order
     */
    public function update(int $id, OrderDTO $dto): Order
    {
        return $this->orderRepository->update($id, $dto->toArray());
    }

    /**
     * Delete an order.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $order = $this->orderRepository->findOrFail($id, ['orderitems']);
        
        // Release stock for all order items
        foreach ($order->orderitems as $orderItem) {
            $this->inventoryService->releaseStock($orderItem->product_id, $orderItem->quantity);
        }
        
        return $this->orderRepository->delete($id);
    }

    /**
     * Get orders by status.
     *
     * @param int $status
     * @param array $relations
     * @return Collection
     */
    public function getByStatus(int $status, array $relations = ['orderitems', 'coupon']): Collection
    {
        return $this->orderRepository->getByStatus($status, $relations);
    }

    /**
     * Get order statistics.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return $this->orderRepository->getStatistics();
    }

    /**
     * Calculate order total from cart items.
     *
     * @param array $cartItems
     * @return float
     */
    public function calculateTotal(array $cartItems): float
    {
        $total = 0;
        
        foreach ($cartItems as $item) {
            $price = (float) ($item['price'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 1);
            $total += $price * $quantity;
        }
        
        return round($total, 2);
    }
}
