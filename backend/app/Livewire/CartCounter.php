<?php

namespace App\Livewire;

use Livewire\Component;
use Hnooz\LaravelCart\Facades\Cart;

class CartCounter extends Component
{
    public $count = 0;
    public $cartItems = [];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = Cart::all();
        $this->count = Cart::count();
    }

    protected $listeners = [
        'cart-updated' => 'loadCart',
        'refresh-cart' => 'loadCart'
    ];
    
    public function updated($propertyName)
    {
        // This ensures the component re-renders when properties change
    }
    
    public function refresh()
    {
        $this->loadCart();
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
