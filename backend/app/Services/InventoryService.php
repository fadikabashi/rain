<?php

namespace App\Services;

use App\Constants\StockThreshold;
use App\Events\ProductStockLow;
use App\Exceptions\InsufficientStockException;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\LoggingService;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected LoggingService $loggingService
    ) {}

    /**
     * Get product by ID.
     *
     * @param int $productId
     * @return \App\Models\Product|null
     */
    public function getProduct(int $productId)
    {
        return $this->productRepository->find($productId);
    }

    /**
     * Check if product is available in requested quantity.
     *
     * Validates:
     * 1. Product exists
     * 2. Product is available (is_available = true)
     * 3. Product has sufficient quantity
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     * @throws InsufficientStockException
     */
    public function checkAvailability(int $productId, int $quantity): bool
    {
        $product = $this->productRepository->find($productId);
        
        // Validate product exists
        if (!$product) {
            throw new InsufficientStockException("Product not found.", 0);
        }
        
        // Validate product is available for purchase
        if (!$product->is_available) {
            throw new InsufficientStockException("Product is currently unavailable.", 0);
        }
        
        // Validate sufficient stock quantity
        if ($product->quantity < $quantity) {
            throw new InsufficientStockException(
                "Insufficient stock. Requested: {$quantity}, Available: {$product->quantity}",
                $product->quantity
            );
        }
        
        return true;
    }

    /**
     * Reserve stock (decrease quantity).
     *
     * Process:
     * 1. Validate availability
     * 2. Calculate new stock level
     * 3. Update stock in database
     * 4. Dispatch low stock event if threshold reached
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     * @throws InsufficientStockException
     */
    public function reserveStock(int $productId, int $quantity): bool
    {
        // Validate stock is available before reserving
        $this->checkAvailability($productId, $quantity);
        
        $product = $this->productRepository->find($productId);
        $newStock = $product->quantity - $quantity;
        
        // Decrease stock quantity (negative value decreases)
        $result = $this->productRepository->updateStock($productId, -$quantity);
        
        // Dispatch low stock event if stock falls below threshold
        if (StockThreshold::isLow($newStock)) {
            event(new ProductStockLow($product->fresh(), $newStock));
            
            // Log low stock warning
            $this->loggingService->logInventory('warning', 'Product stock is low', [
                'product_id' => $productId,
                'product_name' => $product->name_en,
                'current_stock' => $newStock,
                'threshold' => StockThreshold::LOW_STOCK_THRESHOLD,
            ]);
        }
        
        // Log stock reservation
        $this->loggingService->logInventory('info', 'Stock reserved', [
            'product_id' => $productId,
            'quantity_reserved' => $quantity,
            'remaining_stock' => $newStock,
        ]);
        
        return $result;
    }

    /**
     * Release stock (increase quantity) - for order cancellations.
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function releaseStock(int $productId, int $quantity): bool
    {
        $product = $this->productRepository->find($productId);
        
        if (!$product) {
            return false;
        }
        
        $this->productRepository->updateStock($productId, $quantity);
        
        // Re-enable product if it was out of stock
        if ($product->quantity <= 0 && $product->is_available == false) {
            $product->is_available = true;
            $product->save();
        }
        
        return true;
    }

    /**
     * Update stock quantity.
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function updateStock(int $productId, int $quantity): bool
    {
        return $this->productRepository->updateStock($productId, $quantity);
    }

    /**
     * Check multiple products availability.
     *
     * @param array $items [['product_id' => int, 'quantity' => int], ...]
     * @return array ['available' => bool, 'errors' => []]
     */
    public function checkMultipleAvailability(array $items): array
    {
        $errors = [];
        $allAvailable = true;
        
        foreach ($items as $item) {
            try {
                $this->checkAvailability($item['product_id'], $item['quantity']);
            } catch (InsufficientStockException $e) {
                $allAvailable = false;
                $errors[] = [
                    'product_id' => $item['product_id'],
                    'message' => $e->getMessage(),
                    'available_quantity' => $e->getAvailableQuantity(),
                ];
            }
        }
        
        return [
            'available' => $allAvailable,
            'errors' => $errors,
        ];
    }
}
