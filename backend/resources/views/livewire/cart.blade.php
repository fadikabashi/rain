<div>
    <!-- Cart Products -->
    <div class="cart-table table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th class="cart-column-image" scope="col">{{__('frontend.image')}}</th>
                    <th class="cart-column-productname" scope="col">{{__('frontend.product')}}</th>
                    <th class="cart-column-price" scope="col">{{__('frontend.price')}}</th>
                    <th class="cart-column-quantity" scope="col">{{__('frontend.quantity')}}</th>
                    <th class="cart-column-total" scope="col">{{__('frontend.total')}}</th>
                    <th class="cart-column-remove" scope="col">{{__('frontend.remove')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cartItems as $cartItem)
                <tr>
                    <td>
                        @if(isset($cartItem['options']['photo']))
                        <a href="/details/{{$cartItem['id']}}" class="product-image">
                            <img src="{{ asset($cartItem['options']['photo'])}}" alt="product image">
                        </a>
                        @endif
                    </td>
                    <td>
                        <a href="/details/{{$cartItem['id']}}" class="product-title">{{$cartItem['name']}}</a>
                    </td>
                    <td>{{$cartItem['price']}} KWD</td>
                    <td>
                        <input 
                            type="number" 
                            value="{{$cartItem['quantity']}}" 
                            min="1"
                            wire:change="updateQuantity('{{$cartItem['id']}}', $event.target.value)"
                            wire:loading.attr="disabled"
                            class="form-control quantity-input"
                            style="width: 80px;"
                        >
                    </td>
                    <td>
                        <span class="total-price">{{$cartItem['price'] * $cartItem['quantity']}} KWD</span>
                    </td>
                    <td>
                        <button 
                            class="remove-product" 
                            wire:click="removeItem('{{$cartItem['id']}}')"
                            wire:loading.attr="disabled"
                            wire:target="removeItem"
                        >
                            <i class="ion ion-ios-close"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">
                        <p>{{__('frontend.cart_empty')}}</p>
                        <a href="/products" class="ho-button ho-button-sm">
                            <span>{{__('frontend.continue_shopping')}}</span>
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!--// Cart Products -->

    <!-- Cart Content -->
    <div class="cart-content">
        <div class="row justify-content-between">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="cart-content-left">
                    <div class="ho-buttongroup">
                        <a href="/products" class="ho-button ho-button-sm">
                            <span>{{__('frontend.continue_shopping')}}</span>
                        </a>
                        @if(count($cartItems) > 0)
                        <button 
                            class="ho-button ho-button-sm" 
                            wire:click="clearCart"
                            wire:loading.attr="disabled"
                            wire:target="clearCart"
                        >
                            <span wire:loading.remove wire:target="clearCart">
                                {{__('frontend.clear_cart')}}
                            </span>
                            <span wire:loading wire:target="clearCart">
                                Clearing...
                            </span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="cart-content-right">
                    <h2><span>{{__('frontend.cart_totals')}}</span></h2>
                    <table class="cart-pricing-table">
                        <tbody>
                            <tr class="cart-total">
                                <th><span>{{__('frontend.total')}}</span></th>
                                <td wire:key="cart-total-{{ $total }}">{{$total}} KWD</td>
                            </tr>
                        </tbody>
                    </table>
                    @if(count($cartItems) > 0)
                    <a href="/checkout" class="ho-button">
                        <span><span>{{__('frontend.proceed_to_checkout')}}</span></span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--// Cart Content -->
</div>
