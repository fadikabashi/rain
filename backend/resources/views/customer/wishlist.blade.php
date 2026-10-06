@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li><a href="{{ route('customer.account.dashboard') }}">{{ Session::get('locale') === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</a></li>
                <li>{{ Session::get('locale') === 'ar' ? 'قائمة الأمنيات' : 'Wishlist' }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="wishlist-area bg-white ptb-30">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('customer.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="wishlist-content">
                    <h2 class="account-title">{{ Session::get('locale') === 'ar' ? 'قائمة الأمنيات' : 'My Wishlist' }}</h2>

                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($wishlistItems->count() > 0)
                    <div class="wishlist-products mt-30">
                        <div class="row">
                            @foreach($wishlistItems as $item)
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-30">
                                <article class="hoproduct">
                                    <div class="hoproduct-image">
                                        <a class="hoproduct-thumb" href="/details/{{ $item->product->id }}">
                                            <img class="hoproduct-frontimage" src="{{ asset($item->product->photo) }}"
                                                alt="product image" loading="lazy">
                                            <img class="hoproduct-backimage" src="{{ asset($item->product->photo) }}"
                                                alt="product image" loading="lazy">
                                        </a>
                                        <ul class="hoproduct-actionbox">
                                            <li>
                                                @livewire('add-to-cart-component', [
                                                    'productId' => $item->product->id,
                                                    'productName' => Session::get('locale') === 'ar' ? $item->product->name_ar : $item->product->name_en,
                                                    'productPrice' => $item->product->price,
                                                    'productPhoto' => $item->product->photo,
                                                    'compact' => true
                                                ], key('add-to-cart-wishlist-' . $item->product->id))
                                            </li>
                                            <li>
                                                <a href="/details/{{ $item->product->id }}" class="quickview-trigger" data-product-id="{{ $item->product->id }}">
                                                    <i class="lnr lnr-eye"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="remove-wishlist" data-product-id="{{ $item->product->id }}" title="{{ Session::get('locale') === 'ar' ? 'إزالة من قائمة الأمنيات' : 'Remove from Wishlist' }}">
                                                    <i class="lnr lnr-heart"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="hoproduct-flags">
                                            @if($item->product->discount > 0)
                                            <li class="flag-discount">-{{ $item->product->discount * 100 }}%</li>
                                            @endif
                                            @if($item->product->is_available && $item->product->quantity > 0)
                                            <li class="flag-new">{{__('frontend.in_stock')}}</li>
                                            @else
                                            <li class="flag-sale">{{__('frontend.out_of_stock')}}</li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="hoproduct-content">
                                        <h5 class="hoproduct-title">
                                            <a href="/details/{{ $item->product->id }}">
                                                {{ Session::get('locale') === 'ar' ? $item->product->name_ar : $item->product->name_en }}
                                            </a>
                                        </h5>
                                        <div class="hoproduct-pricebox">
                                            @if($item->product->discount > 0)
                                            <span class="price">{{ $item->product->price - ($item->product->discount * $item->product->price) }} KWD</span>
                                            <span class="oldprice">{{ $item->product->price }} KWD</span>
                                            @else
                                            <span class="price">{{ $item->product->price }} KWD</span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="no-wishlist text-center py-5">
                        <i class="lnr lnr-heart" style="font-size: 64px; color: #ccc;"></i>
                        <h3 class="mt-3">{{ Session::get('locale') === 'ar' ? 'قائمة الأمنيات فارغة' : 'Wishlist is Empty' }}</h3>
                        <p class="text-muted">{{ Session::get('locale') === 'ar' ? 'لم تقم بإضافة أي منتجات إلى قائمة الأمنيات بعد' : 'You haven\'t added any products to your wishlist yet' }}</p>
                        <a href="/products" class="btn btn-primary mt-3">
                            {{ Session::get('locale') === 'ar' ? 'تصفح المنتجات' : 'Browse Products' }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle remove from wishlist
    document.querySelectorAll('.remove-wishlist').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            
            if (confirm('{{ Session::get('locale') === 'ar' ? 'هل تريد إزالة هذا المنتج من قائمة الأمنيات؟' : 'Are you sure you want to remove this product from wishlist?' }}')) {
                fetch(`/wishlist/remove/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the product card
                        this.closest('.col-lg-4').remove();
                        
                        // Update wishlist count in header
                        const countEl = document.getElementById('wishlistCount');
                        if (countEl) {
                            countEl.textContent = data.count || 0;
                        }
                        
                        // Show success message
                        alert(data.message || '{{ Session::get('locale') === 'ar' ? 'تمت الإزالة بنجاح' : 'Removed successfully' }}');
                        
                        // Reload if no items left
                        if (data.count === 0) {
                            location.reload();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('{{ Session::get('locale') === 'ar' ? 'حدث خطأ' : 'An error occurred' }}');
                });
            }
        });
    });
});
</script>
@endsection
