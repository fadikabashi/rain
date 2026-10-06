<div wire:key="cart-counter-{{ $count }}">
    <a class="header-carticon" href="/cart">
        <i class="lnr lnr-cart"></i>
        <span class="count" wire:key="cart-count-{{ $count }}">{{ $count }}</span>
    </a>

    <!-- Minicart -->
    <div class="header-minicart minicart">
        <div class="minicart-header">
            @forelse($cartItems as $cartItem)
            <div class="minicart-product">
                <div class="minicart-productimage">
                    <a href="/details/{{$cartItem['id']}}">
                        @if(isset($cartItem['options']['photo']))
                        <img src="{{ asset($cartItem['options']['photo'])}}" alt="product image">
                        @else
                        <img src="{{ asset('images/product/thumbnail/product-image-2.jpg') }}" alt="product image">
                        @endif
                    </a>
                    <span class="minicart-productquantity">{{$cartItem['quantity']}}x</span>
                </div>
                <div class="minicart-productcontent">
                    <h6><a href="/details/{{$cartItem['id']}}">{{$cartItem['name']}}</a></h6>
                    <span class="minicart-productprice">{{$cartItem['price']}} KWD</span>
                </div>
                <form action="{{route('cart.remove')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $cartItem['id'] }}" name="id">
                    <button class="minicart-productclose" type="submit"><i class="ion ion-ios-close-circle"></i></button>
                </form>
            </div>
            @empty
            <div class="minicart-empty">
                <p>{{__('frontend.cart_empty')}}</p>
            </div>
            @endforelse
        </div>
        @if(count($cartItems) > 0)
        <ul class="minicart-pricing">
            <li>{{__('frontend.total')}} <span>{{ Cart::total() }} KWD</span></li>
        </ul>
        <div class="minicart-footer">
            <a href="/cart" class="ho-button ho-button-fullwidth">
                <span>{{__('frontend.view_cart')}}</span>
            </a>
            <a href="/checkout" class="ho-button ho-button-dark ho-button-fullwidth">
                <span>{{__('frontend.proceed_to_checkout')}}</span>
            </a>
        </div>
        @endif
    </div>
    <!--// Minicart -->
</div>
