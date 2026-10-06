@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="index.html">{{__('frontend.home')}}</a></li>
                <li>{{__('frontend.shop')}}</li>
            </ul>
        </div>
    </div>
</div>
@if ($message = Session::get('success'))
<div class="p-4 mb-3 bg-green-400 rounded">
    <p class="text-green-800">{{ $message }}</p>
</div>
@endif
<div class="shop-page-area bg-white ptb-30">
    <div class="container">

        <div class="banner-area">
            @foreach ($ading as $ad)
            <div class="imgbanner imgbanner-2">
                <a href="/products">
                    <img src="{{ asset($ad->ad_1_shop) }}" alt="banner">
                </a>
            </div>
            @endforeach
        </div>

        <div class="shop-filters mt-30">
            <div class="shop-filters-viewmode">
                <button class="is-active" data-view="grid"><i class="ion ion-ios-keypad"></i></button>
                <button data-view="list"><i class="ion ion-ios-list"></i></button>
            </div>
            <span class="shop-filters-viewitemcount">{{__('frontend.there_are')}} {{$products_count}} {{__('frontend.products')}}</span>
            <div class="shop-filters-sortby">
                <b>{{__('frontend.sort_by')}}:</b>
                
            </div>
        </div>

        <div class="shop-page-products mt-30">
            <div class="row no-gutters">
                
                    @foreach ($products as $product)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <!-- Single Product -->
                    <article class="hoproduct">
                        <div class="hoproduct-image">
                            <a class="hoproduct-thumb" href="/details/{{$product->id}}">
                                <img class="hoproduct-frontimage" src="{{ asset($product->photo) }}"
                                    alt="product image" loading="lazy">
                                <img class="hoproduct-backimage" src="{{ asset($product->photo) }}"
                                    alt="product image" loading="lazy">
                            </a>
                            <ul class="hoproduct-actionbox">
                                <li>
                                    @livewire('add-to-cart-component', [
                                        'productId' => $product->id,
                                        'productName' => Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en,
                                        'productPrice' => $product->price,
                                        'productPhoto' => $product->photo,
                                        'compact' => true
                                    ], key('add-to-cart-' . $product->id))
                                </li>
                                <li><a href="/details/{{$product->id}}" class="quickview-trigger" data-product-id="{{$product->id}}"><i class="lnr lnr-eye"></i></a></li>
                                <li>
                                    <a href="#" class="wishlist-toggle" data-product-id="{{ $product->id }}" title="{{ Session::get('locale') === 'ar' ? 'إضافة إلى قائمة الأمنيات' : 'Add to Wishlist' }}">
                                        <i class="lnr lnr-heart"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="add-to-comparison-btn" data-product-id="{{ $product->id }}" title="{{ __('frontend.add_to_comparison') }}">
                                        <i class="lnr lnr-layers"></i>
                                    </a>
                                </li>
                            </ul>
                            <ul class="hoproduct-flags">
                                @if($product->discount_rate > 0)
                                <li class="flag-discount">-{{$product->discount_percentage}}%</li>
                                @endif
                                @if($product->is_available && $product->quantity > 0)
                                <li class="flag-new">{{__('frontend.in_stock')}}</li>
                                @if($product->quantity <= 10)
                                <li class="flag-sale" style="background-color: #ff9800;">{{__('frontend.only')}} {{$product->quantity}} {{__('frontend.left')}}</li>
                                @endif
                                @else
                                <li class="flag-sale">{{__('frontend.out_of_stock')}}</li>
                                @endif
                            </ul>
                        </div>
                        <div class="hoproduct-content">
                            @if (Session::get('locale') ==='ar')
                            <h5 class="hoproduct-title"><a href="product-details.html">{{$product->name_ar}}</a></h5>
                            <div class="hoproduct-pricebox">
                                <div class="pricebox">
                                    @if($product->discount_rate > 0)
                                    <del class="oldprice">{{$product->price}} KWD</del>
                                    <span class="price">{{$product->price_after_discount}} KWD</span>
                                    @else
                                    <span class="price">{{$product->price}} KWD</span>
                                    @endif
                                </div>
                            </div>
                            <p class="hoproduct-content-description">
                                {{$product->description_ar}}
                            </p>
                            @else
                            <h5 class="hoproduct-title"><a href="/details/{{$product->id}}" >{{$product->name_en}}</a></h5>
                            <div class="hoproduct-pricebox">
                                <div class="pricebox">
                                    @if($product->discount_rate > 0)
                                    <del class="oldprice">{{$product->price}} KWD</del>
                                    <span class="price">{{$product->price_after_discount}} KWD</span>
                                    @else
                                    <span class="price">{{$product->price}} KWD</span>
                                    @endif
                                </div>
                            </div>
                            <p class="hoproduct-content-description">
                                {{$product->description_en}}
                            </p>
                            @endif
                        
                        </div>
                    </article>
                    </div>
                    @endforeach
                    <!--// Single Product -->
            </div>
        </div>

    </div>
</div>
    
@endsection