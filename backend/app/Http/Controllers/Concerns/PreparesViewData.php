<?php

namespace App\Http\Controllers\Concerns;

use Hnooz\LaravelCart\Facades\Cart;

/**
 * Trait for preparing common view data.
 */
trait PreparesViewData
{
    /**
     * Get cart items for views.
     *
     * @return array
     */
    protected function getCartItems(): array
    {
        return Cart::all();
    }

    /**
     * Prepare view data with cart items.
     *
     * @param string $viewName
     * @param array $data Additional data to pass to view
     * @return \Illuminate\View\View
     */
    protected function viewWithCart(string $viewName, array $data = [])
    {
        return view($viewName, array_merge($data, [
            'cartItems' => $this->getCartItems(),
        ]));
    }
}
