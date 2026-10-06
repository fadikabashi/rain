<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartStockService;
use Hnooz\LaravelCart\Facades\Cart;
use Livewire\Component;

class CartComponent extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $isLoading = false;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = Cart::all();
        $this->total = Cart::total();
    }

    public function updateQuantity($itemId, $quantity)
    {
        $this->isLoading = true;

        try {
            $items = Cart::all();
            $currentItem = collect($items)->firstWhere('id', $itemId);

            if ($currentItem) {
                $currentQuantity = $currentItem['quantity'];
                $newQuantity = max(1, (int) $quantity);

                if ($newQuantity > 100) {
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => __('frontend.cart_item_quantity_max'),
                    ]);
                    $this->loadCart();

                    return;
                }

                $product = Product::find($itemId);
                if (! $product) {
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => __('frontend.product_not_found'),
                    ]);
                    $this->loadCart();

                    return;
                }

                if (! CartStockService::canSetLineQuantity($product, $newQuantity)) {
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => __('frontend.insufficient_stock', [
                            'requested' => $newQuantity,
                            'available' => $product->quantity,
                        ]),
                    ]);
                    $this->loadCart();

                    return;
                }

                if ($newQuantity > $currentQuantity) {
                    Cart::increase($itemId, $newQuantity - $currentQuantity);
                } elseif ($newQuantity < $currentQuantity) {
                    Cart::decrease($itemId, $currentQuantity - $newQuantity);
                }
            }

            $this->loadCart();
            
            $this->dispatch('cart-updated');
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('frontend.cart_updated_successfully')
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('frontend.failed_to_update_cart')
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    public function removeItem($itemId)
    {
        $this->isLoading = true;

        try {
            Cart::remove($itemId);
            $this->loadCart();
            
            $this->dispatch('cart-updated');
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('frontend.item_removed_from_cart')
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('frontend.failed_to_remove_item')
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    public function clearCart()
    {
        $this->isLoading = true;

        try {
            Cart::clear();
            $this->loadCart();
            
            $this->dispatch('cart-updated');
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('frontend.cart_cleared_successfully')
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('frontend.failed_to_clear_cart')
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.cart-component');
    }
}
