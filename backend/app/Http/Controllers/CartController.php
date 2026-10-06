<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\AddToCartRequest;
use App\Services\CartStockService;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Hnooz\LaravelCart\Facades\Cart;

class CartController extends Controller
{
    public function __construct(
        protected LoggingService $loggingService
    ) {}

    /**
     * Display the shopping cart.
     *
     * @return \Illuminate\View\View
     */
    public function cartList()
    {
        $cartItems = Cart::all();
        return view('frontend.cart', compact('cartItems'));
    }

    /**
     * Add a product to the shopping cart.
     *
     * @param AddToCartRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(AddToCartRequest $request)
    {
        try {
            $product = Product::findOrFail($request->id);
            $unitPrice = (float) $product->price_after_discount;

            Cart::add(
                $request->id,
                $request->name,
                $unitPrice,
                (int) $request->quantity,
                ['photo' => $request->photo]
            );

            // Log successful cart addition
            $this->loggingService->logCart('info', 'Product added to cart', [
                'product_id' => $request->id,
                'product_name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $unitPrice,
            ]);

            return back()->with('success', __('frontend.product_added_to_cart'));
        } catch (\Exception $e) {
            $this->loggingService->logCart('error', 'Add to cart failed', [
                'error' => $e->getMessage(),
                'product_id' => $request->id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', __('frontend.failed_to_add_to_cart'));
        }
    }

    /**
     * Update cart item quantity.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $product = Product::find($validated['id']);
        if (! $product) {
            return redirect()->route('cart.list')->with('error', __('frontend.product_not_found'));
        }

        $newQuantity = (int) $validated['quantity'];

        if (! CartStockService::canSetLineQuantity($product, $newQuantity)) {
            return redirect()->route('cart.list')->with('error', __('frontend.insufficient_stock', [
                'requested' => $newQuantity,
                'available' => $product->quantity,
            ]));
        }

        $items = Cart::all();
        $currentItem = collect($items)->first(function ($item) use ($validated) {
            return (string) ($item['id'] ?? '') === (string) $validated['id'];
        });

        if ($currentItem) {
            $currentQuantity = $currentItem['quantity'];

            if ($newQuantity > $currentQuantity) {
                Cart::increase((string) $validated['id'], $newQuantity - $currentQuantity);
            } elseif ($newQuantity < $currentQuantity) {
                Cart::decrease((string) $validated['id'], $currentQuantity - $newQuantity);
            }
        }

        session()->flash('success', __('frontend.item_cart_updated'));

        return redirect()->route('cart.list');
    }

    /**
     * Remove an item from the cart.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeCart(Request $request)
    {
        Cart::remove($request->id);
        session()->flash('success', __('frontend.item_cart_removed'));

        return redirect()->route('cart.list');
    }

    /**
     * Clear all items from the cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearAllCart()
    {
        Cart::clear();
        session()->flash('success', __('frontend.all_items_cart_cleared'));

        return redirect()->route('cart.list');
    }
}
