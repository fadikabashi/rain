<?php

namespace App\Livewire;

use Livewire\Component;
use Hnooz\LaravelCart\Facades\Cart;
use App\Services\CartStockService;
use App\Services\InventoryService;
use App\Services\LoggingService;
use App\Livewire\CartCounter;

class AddToCartComponent extends Component
{
    public $productId;
    public $productName;
    public $productPrice;
    public $productPhoto;
    public $quantity = 1;
    public $maxQuantity = 1;
    public $isLoading = false;
    public $message = '';
    public $messageType = '';
    public $compact = false;

    public bool $canAddMore = true;

    public function mount($productId, $productName, $productPrice, $productPhoto = null, $compact = false)
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->productPrice = $productPrice;
        $this->productPhoto = $productPhoto;
        $this->compact = $compact;

        $this->refreshQuantityLimits();
    }

    /**
     * Remaining units that can be added for this product (stock minus already in cart).
     */
    protected function refreshQuantityLimits(): void
    {
        $inventoryService = app(InventoryService::class);
        $product = $inventoryService->getProduct($this->productId);
        if (! $product) {
            $this->maxQuantity = 0;
            $this->canAddMore = false;

            return;
        }

        $inCart = CartStockService::quantityInCart($this->productId);
        $remaining = max(0, (int) $product->quantity - $inCart);
        $this->maxQuantity = $remaining;
        $this->canAddMore = $remaining > 0;

        if ($this->maxQuantity > 0) {
            $this->quantity = min($this->quantity, $this->maxQuantity);
        }
    }

    public function increment()
    {
        $this->refreshQuantityLimits();
        if ($this->maxQuantity > 0 && $this->quantity < $this->maxQuantity) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        // Set loading state immediately
        $this->isLoading = true;
        $this->message = '';
        $this->messageType = '';

        \Log::info('AddToCartComponent: addToCart called', [
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
        ]);

        try {
            // Validate stock availability - do validation directly to avoid exception render() issues
            $inventoryService = app(InventoryService::class);
            
            // Check if product exists and is available
            $product = $inventoryService->getProduct($this->productId);
            
            if (!$product) {
                $this->isLoading = false;
                $this->dispatch('show-alert', ['type' => 'error', 'message' => __('frontend.product_not_found')]);
                return;
            }
            
            if (!$product->is_available) {
                $this->isLoading = false;
                $this->dispatch('show-alert', ['type' => 'error', 'message' => __('frontend.product_unavailable')]);
                return;
            }
            
            if (! CartStockService::canMergeAdd($product, (int) $this->quantity)) {
                $this->isLoading = false;
                $totalAfter = CartStockService::totalAfterAdd($this->productId, (int) $this->quantity);
                $this->dispatch('show-alert', ['type' => 'error', 'message' => __('frontend.insufficient_stock', ['requested' => $totalAfter, 'available' => $product->quantity])]);
                return;
            }

            // Add to cart
            $unitPrice = (float) $product->price_after_discount;
            Cart::add(
                $this->productId,
                $this->productName,
                $unitPrice,
                (int) $this->quantity,
                ['photo' => $this->productPhoto]
            );

            $cartCount = Cart::count();

            // Log successful cart addition
            try {
                $loggingService = app(LoggingService::class);
                $loggingService->logCart('info', 'Product added to cart', [
                    'product_id' => $this->productId,
                    'product_name' => $this->productName,
                    'quantity' => $this->quantity,
                    'price' => $unitPrice,
                    'cart_count' => $cartCount,
                ]);
            } catch (\Exception $logError) {
                // Don't fail if logging fails
            }

            // Reset quantity after successful add
            $this->quantity = 1;
            $this->refreshQuantityLimits();

            \Log::info('AddToCartComponent: Dispatching events');
            
            // Dispatch events
            // Dispatch to cart counter component (target by class to avoid name mismatch)
            $this->dispatch('cart-updated')->to(CartCounter::class);
            $this->dispatch('refresh-cart')->to(CartCounter::class);
            
            // Broadcast globally for other listeners
            $this->dispatch('cart-updated');
            
            // Dispatch alert event
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('frontend.product_added_to_cart')
            ]);
            
            \Log::info('AddToCartComponent: Events dispatched successfully');

        } catch (\Exception $e) {
            // Log error but don't let it break the component
            \Log::error('AddToCartComponent error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'product_id' => $this->productId,
            ]);
            
            try {
                $loggingService = app(LoggingService::class);
                $loggingService->logCart('error', 'Add to cart failed', [
                    'error' => $e->getMessage(),
                    'product_id' => $this->productId,
                    'trace' => $e->getTraceAsString(),
                ]);
            } catch (\Exception $logError) {
                // Ignore logging errors
            }

            $this->dispatch('show-alert', ['type' => 'error', 'message' => $e->getMessage() ?: __('frontend.failed_to_add_to_cart')]);
            
            // (Removed dispatchBrowserEvent usage; Livewire v4 uses $this->dispatch())
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return $this->compact 
            ? view('livewire.add-to-cart-compact')
            : view('livewire.add-to-cart-component');
    }
}
