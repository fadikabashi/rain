@extends(Session::get('locale') === 'ar' ? 'layouts.main-rtl' : 'layouts.main')

@push('structured-data')
    @include('partials.structured-data', ['pageType' => 'home'])
@endpush

@section('content')
    <div class="rain-home-compact">
    <!-- Banners Area -->
    <div class="banners-area pb-30 bg-grey">
        <div class="container">
            <div class="row">
               
                <!--<div class="col-lg-3">-->
                    <!-- Category Menu -->
                <!--    <div class="catmenu catmenu-2 mt-30">-->
                <!--        <button class="catmenu-trigger is-active">-->
                <!--            <span>{{ __('frontend.categories') }}</span>-->
                <!--        </button>-->
                <!--        <nav class="catmenu-body">-->
                <!--            <ul>-->
                <!--                @foreach ($categories as $category)-->
                <!--                    @if (Session::get('locale') === 'en')-->
                <!--                        <li><a href="/products">-->
                <!--                                @if (!$category->image)-->
                <!--                                    <i class="ion ion-logo-game-controller-b"></i>-->
                <!--                                @else-->
                <!--                                    <img src="{{ $category->image }}"-->
                <!--                                        style="object-fit: cover; width:21px; height:20px; border-radius:50%; vertical-align:middle; border-style:none;">-->
                <!--                                @endif-->
                <!--                                {{ $category->name_en }}-->
                <!--                            </a></li>-->
                <!--                    @else-->
                <!--                        <li><a href="/products">-->
                <!--                                @if (!$category->image)-->
                <!--                                    <i class="ion ion-logo-game-controller-b"></i>-->
                <!--                                @else-->
                <!--                                    <img src="{{ $category->image }}"-->
                <!--                                        style="object-fit: cover; width:21px; height:20px; border-radius:50%; vertical-align:middle; border-style:none;">-->
                <!--                                @endif-->
                <!--                                {{ $category->name_ar }}-->
                <!--                            </a></li>-->
                <!--                    @endif-->
                <!--                @endforeach-->
                <!--            </ul>-->
                <!--        </nav>-->
                <!--    </div>-->
                <!--   // Category Menu -->
                <!--</div>-->
                <div class="col-lg-12">
                    <!-- Hero Area -->
                    <div id="homeHeroCarousel" class="carousel slide herobanner herobanner-3 mt-30 bootstrap-carousel"
                        data-bs-ride="carousel" data-bs-interval="6000" data-bs-pause="hover">
                        <div class="carousel-inner">
                            @foreach ($sildering as $slider)
                                <div class="carousel-item{{ $loop->first ? ' active' : '' }}">
                                    <div class="herobanner-single">
                                        <img src="{{ asset($slider->ad_825) }}" alt="hero image">
                                        <!--<div class="herobanner-content">-->
                                        <!--    <div class="herobanner-box">-->
                                        <!--        <h4>{{ $slider->sub_heading }}</h4>-->
                                        <!--    </div>-->
                                        <!--    <div class="herobanner-box">-->
                                        <!--        <h1>{{ $slider->heading }}</h1>-->
                                        <!--    </div>-->
                                        <!--    <div class="herobanner-box">-->
                                        <!--        <p>{{ $slider->description }}</p>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <span class="herobanner-progress"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#homeHeroCarousel"
                            data-bs-slide="prev" aria-label="Previous slide">
                            <span class="slider-navigation-arrow slider-navigation-prev" aria-hidden="true">
                                <i class="ion ion-ios-arrow-back"></i>
                            </span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#homeHeroCarousel"
                            data-bs-slide="next" aria-label="Next slide">
                            <span class="slider-navigation-arrow slider-navigation-next" aria-hidden="true">
                                <i class="ion ion-ios-arrow-forward"></i>
                            </span>
                        </button>
                    </div>
                    <div class="slider-navigation mt-30">
                        <div class="row">
                            @foreach ($ading as $ad)
                                <div class="col-md-4">
                                    <div class="imgbanner">
                                        <a href="/products">
                                            <img src="{{ asset($ad->ad_1_350) }}" alt="banner">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="imgbanner">
                                        <a href="/products">
                                            <img src="{{ asset($ad->ad_2_350) }}" alt="banner">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="imgbanner">
                                        <a href="/products">
                                            <img src="{{ asset($ad->ad_3_350) }}" alt="banner">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!--// Hero Area -->
                </div>
            </div>
        </div>
    </div>
    <!-- Trust Badges Strip -->
    <div class="trust-badges">
        <div class="container">
            <div class="trust-badges-grid">
                <div class="trust-badge-item">
                    <div class="trust-badge-icon"><i class="lnr lnr-rocket"></i></div>
                    <div class="trust-badge-text">
                        <h6>{{ Session::get('locale') === 'ar' ? 'شحن سريع' : 'Fast Delivery' }}</h6>
                        <p>{{ Session::get('locale') === 'ar' ? 'الكويت وجميع المناطق' : 'Kuwait & all regions' }}</p>
                    </div>
                </div>
                <div class="trust-badge-item">
                    <div class="trust-badge-icon"><i class="lnr lnr-phone-handset"></i></div>
                    <div class="trust-badge-text">
                        <h6>{{ Session::get('locale') === 'ar' ? 'دعم متخصص' : 'Expert Support' }}</h6>
                        <p>+(965) 699 08320</p>
                    </div>
                </div>
                <div class="trust-badge-item">
                    <div class="trust-badge-icon"><i class="lnr lnr-lock"></i></div>
                    <div class="trust-badge-text">
                        <h6>{{ Session::get('locale') === 'ar' ? 'دفع آمن' : 'Secure Payment' }}</h6>
                        <p>{{ Session::get('locale') === 'ar' ? 'بيانات مشفرة 100%' : '100% protected data' }}</p>
                    </div>
                </div>
                <div class="trust-badge-item">
                    <div class="trust-badge-icon"><i class="lnr lnr-checkmark-circle"></i></div>
                    <div class="trust-badge-text">
                        <h6>{{ Session::get('locale') === 'ar' ? 'ضمان الجودة' : 'Quality Guarantee' }}</h6>
                        <p>{{ Session::get('locale') === 'ar' ? 'منتجات أصلية معتمدة' : 'Certified original products' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--// Banners Area -->
    <div class="ho-section categories-area mt-30">
        <div class="container">
            <div class="section-title">
                <h3>{{ __('frontend.our_categories') }}</h3>
            </div>
            <div class="categories-slider-2 slider-navigation-2 slider-navigation-2-m0">
                @foreach ($categories as $category)
                    <div class="category-wrapper">
                        <!-- Single Category -->
                        <div class="category">
                            <a href="/products"
                                class="category-thumb{{ $category->photo ? '' : ' category-thumb-placeholder' }}">

                                @if ($category->image)
                                    <img src="{{ asset($category->image) }}"
                                        alt="{{ Session::get('locale') === 'ar' ? $category->name_ar : $category->name_en }}">
                                @else
                                    <span class="category-thumb-icon"><i class="lnr lnr-layers"></i></span>
                                @endif
                            </a>
                            <div class="category-content">
                                @if (Session::get('locale') === 'ar')
                                    <h5 class="category-title">{{ $category->name_ar }}</h5>
                                @else
                                    <h5 class="category-title">{{ $category->name_en }}</h5>
                                @endif
                                <span
                                    class="category-productcounter">{{ $products->where('category_id', $category->id)->count() }}{{ __('frontend.products') }}</span>
                                <a href="/products" class="category-productlink">{{ __('frontend.shop_now') }} <i
                                        class="ion ion-md-arrow-dropleft"></i></a>
                            </div>
                        </div>
                        <!--// Single Category -->
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="ho-section deal-of-the-day-area bg-white pt-30">
        <div class="container">
            <div class="deal-header">
                <h3>{{ __('frontend.deal_of_the_day') }}</h3>
                <div class="deal-countdown" aria-live="polite">
                    <span
                        class="deal-countdown-label">{{ Session::get('locale') === 'ar' ? 'ينتهي في:' : 'Ends in:' }}</span>
                    <div class="countdown-unit"><span
                            id="countdown-h">00</span><small>{{ Session::get('locale') === 'ar' ? 'ساعة' : 'HRS' }}</small>
                    </div>
                    <span class="countdown-sep" aria-hidden="true">:</span>
                    <div class="countdown-unit"><span
                            id="countdown-m">00</span><small>{{ Session::get('locale') === 'ar' ? 'دقيقة' : 'MIN' }}</small>
                    </div>
                    <span class="countdown-sep" aria-hidden="true">:</span>
                    <div class="countdown-unit"><span
                            id="countdown-s">00</span><small>{{ Session::get('locale') === 'ar' ? 'ثانية' : 'SEC' }}</small>
                    </div>
                </div>
            </div>
            <div class="product-slider deal-of-the-day-slider slider-navigation-2 slider-dots">

                @foreach ($best_products as $product)
                    <div class="product-slider-col">
                        <!-- Single Product -->
                        <article class="hoproduct">

                            <div class="hoproduct-image">
                                <a class="hoproduct-thumb" href="/details/{{ $product->id }}">
                                    <img class="hoproduct-frontimage" src="{{ asset($product->photo) }}"
                                        alt="product image" loading="lazy">
                                    <img class="hoproduct-backimage" src="{{ asset($product->photo) }}"
                                        alt="product image" loading="lazy">
                                </a>
                                <ul class="hoproduct-actionbox">
                                    <li>
                                        @livewire(
                                            'add-to-cart-component',
                                            [
                                                'productId' => $product->id,
                                                'productName' => Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en,
                                                'productPrice' => $product->price,
                                                'productPhoto' => $product->photo,
                                                'compact' => true,
                                            ],
                                            key('add-to-cart-index-' . $product->id)
                                        )
                                    </li>
                                    <li><a href="/details/{{ $product->id }}" class="quickview-trigger"
                                            data-product-id="{{ $product->id }}"><i class="lnr lnr-eye"></i></a></li>
                                </ul>
                                <ul class="hoproduct-flags">
                                    @if ($product->discount_rate > 0)
                                        <li class="flag-discount">-{{ $product->discount_percentage }}%</li>
                                    @endif
                                    @if ($product->is_available && $product->quantity > 0)
                                        <li class="flag-new">{{ __('frontend.in_stock') }}</li>
                                        @if ($product->quantity <= 10)
                                            <li class="flag-sale flag-low-stock">{{ __('frontend.only') }}
                                                {{ $product->quantity }} {{ __('frontend.left') }}</li>
                                        @endif
                                    @else
                                        <li class="flag-sale">{{ __('frontend.out_of_stock') }}</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="hoproduct-content">
                                @if (Session::get('locale') === 'ar')
                                    <h5 class="hoproduct-title"><a
                                            href="/details/{{ $product->id }}">{{ $product->name_ar }}</a></h5>
                                @else
                                    <h5 class="hoproduct-title"><a
                                            href="/details/{{ $product->id }}">{{ $product->name_en }}</a></h5>
                                @endif
                                @include('partials.product-stars')
                                <div class="hoproduct-pricebox">
                                    <div class="pricebox">
                                        @if ($product->discount_rate > 0)
                                            <del class="oldprice">{{ $product->price }} KWD</del>
                                            <span class="price">{{ $product->price_after_discount }} KWD</span>
                                        @else
                                            <span class="price">{{ $product->price }} KWD</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
                <!--// Single Product -->

                <!--// Single Product -->

            </div>
        </div>
    </div>
    <!-- Banner Area -->
    <div class="banner-area">
        <div class="container">
            @foreach ($ading as $ad)
                <div class="row">

                    <div class="col-md-6">
                        <div class="imgbanner imgbanner-2 mt-30">
                            <a href="/products">
                                <img src="{{ asset($ad->ad_1_555) }}" alt="banner">
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="imgbanner imgbanner-2 mt-30">
                            <a href="/products">
                                <img src="{{ asset($ad->ad_2_555) }}" alt="banner">
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
    <!--// Banner Area -->

    <!-- Trending Products Area -->
    <div class="trending-products-area pt-30">
        <div class="container">
            <div class="section-title">
                <h3>{{ __('frontend.trending_products') }}</h3>
            </div>

            <div class="tab-content" id="bstab1-ontent">
                <div class="tab-pane fade show active" id="bstab1-area1" role="tabpanel"
                    aria-labelledby="bstab1-area1-tab">
                    <div class="product-slider trending-products-slider-2 slider-navigation-2">


                        <!-- Single Product -->
                        @foreach ($products as $product)
                            <div class="product-slider-col">
                                <article class="hoproduct hoproduct-3">

                                    <div class="hoproduct-image">
                                        <a class="hoproduct-thumb" href="/details/{{ $product->id }}">
                                            <img class="hoproduct-frontimage" src="{{ asset($product->photo) }}"
                                                alt="product image">
                                            <img class="hoproduct-backimage" src="{{ asset($product->photo) }}"
                                                alt="product image">
                                        </a>
                                        <ul class="hoproduct-actionbox">
                                            <li>
                                                @livewire(
                                                    'add-to-cart-component',
                                                    [
                                                        'productId' => $product->id,
                                                        'productName' => Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en,
                                                        'productPrice' => $product->price,
                                                        'productPhoto' => $product->photo,
                                                        'compact' => true,
                                                    ],
                                                    key('add-to-cart-best-' . $product->id)
                                                )
                                            </li>
                                            <li><a href="/details/{{ $product->id }}" class="quickview-trigger"
                                                    data-product-id="{{ $product->id }}"><i class="lnr lnr-eye"></i></a>
                                            </li>
                                            <li>
                                                <a href="#" class="wishlist-toggle"
                                                    data-product-id="{{ $product->id }}"
                                                    title="{{ Session::get('locale') === 'ar' ? 'إضافة إلى قائمة الأمنيات' : 'Add to Wishlist' }}">
                                                    <i class="lnr lnr-heart"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="hoproduct-flags">
                                            <li class="flag-pack">{{ __('frontend.new') }}</li>
                                            <li class="flag-discount">-{{ $product->discount_percentage }}%</li>
                                        </ul>
                                    </div>
                                    <div class="hoproduct-content">
                                        @if (Session::get('locale') === 'ar')
                                            <h5 class="hoproduct-title"><a
                                                    href="/details/{{ $product->id }}">{{ $product->name_ar }}</a></h5>
                                        @else
                                            <h5 class="hoproduct-title"><a
                                                    href="/details/{{ $product->id }}">{{ $product->name_en }}</a></h5>
                                        @endif
                                        @include('partials.product-stars')
                                        <div class="hoproduct-pricebox">
                                            <div class="pricebox">
                                                @if ($product->discount_rate > 0)
                                                    <del class="oldprice">{{ $product->price }} KWD</del>
                                                    <span class="price">{{ $product->price_after_discount }} KWD</span>
                                                @else
                                                    <span class="price">{{ $product->price }} KWD</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </article>
                            </div>
                        @endforeach

                        <!--// Single Product -->


                    </div>
                </div>
                {{-- <div class="tab-pane fade" id="bstab1-area2" role="tabpanel" aria-labelledby="bstab1-area2-tab">
                <div class="product-slider trending-products-slider-2 slider-navigation-2">

            </div>
            <div class="tab-pane fade" id="bstab1-area3" role="tabpanel" aria-labelledby="bstab1-area3-tab">
                <div class="product-slider trending-products-slider-2 slider-navigation-2">

                </div>
            </div>
        </div> --}}
            </div>
        </div>
    </div>
    <!--// Trending Products Area -->

    <!-- Our Products Area -->
    <div class="our-products-area pt-30">
        <div class="container">
            <div class="section-title">
                <h3>{{ __('frontend.our_products') }}</h3>
            </div>

            <div class="tab-content" id="bstab2-ontent">
                <div class="tab-pane fade show active" id="bstab2-area1" role="tabpanel"
                    aria-labelledby="bstab2-area1-tab">
                    <div class="product-slider our-products-slider-3 slider-navigation-2">


                        <!-- Single Product -->
                        @foreach ($products as $product)
                            <div class="product-slider-col">
                                <article class="hoproduct flex-row">

                                    <div class="hoproduct-image">
                                        <a class="hoproduct-thumb" href="/details/{{ $product->id }}">
                                            <img class="hoproduct-frontimage" src="{{ asset($product->photo) }}"
                                                alt="product image">
                                            <img class="hoproduct-backimage" src="{{ asset($product->photo) }}"
                                                alt="product image">
                                        </a>
                                        <ul class="hoproduct-actionbox">
                                            <li>
                                                @livewire(
                                                    'add-to-cart-component',
                                                    [
                                                        'productId' => $product->id,
                                                        'productName' => Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en,
                                                        'productPrice' => $product->price,
                                                        'productPhoto' => $product->photo,
                                                        'compact' => true,
                                                    ],
                                                    key('add-to-cart-discount-' . $product->id)
                                                )
                                            </li>
                                            <li><a href="/details/{{ $product->id }}" class="quickview-trigger"
                                                    data-product-id="{{ $product->id }}"><i
                                                        class="lnr lnr-eye"></i></a></li>
                                            <li>
                                                <a href="#" class="wishlist-toggle"
                                                    data-product-id="{{ $product->id }}"
                                                    title="{{ Session::get('locale') === 'ar' ? 'إضافة إلى قائمة الأمنيات' : 'Add to Wishlist' }}">
                                                    <i class="lnr lnr-heart"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="hoproduct-flags">
                                            <li class="flag-pack">{{ __('frontend.new') }}</li>
                                            <li class="flag-discount">-{{ $product->discount_percentage }}%</li>
                                        </ul>
                                    </div>
                                    <div class="hoproduct-content">
                                        @if (Session::get('locale') === 'ar')
                                            <h5 class="hoproduct-title"><a
                                                    href="/details/{{ $product->id }}">{{ $product->name_ar }}</a>
                                            </h5>
                                        @else
                                            <h5 class="hoproduct-title"><a
                                                    href="/details/{{ $product->id }}">{{ $product->name_en }}</a>
                                            </h5>
                                        @endif
                                        @include('partials.product-stars')
                                        <div class="hoproduct-pricebox">
                                            <div class="pricebox">
                                                @if ($product->discount_rate > 0)
                                                    <del class="oldprice">{{ $product->price }} KWD</del>
                                                    <span class="price">{{ $product->price_after_discount }} KWD</span>
                                                @else
                                                    <span class="price">{{ $product->price }} KWD</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach



                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--// Our Products Area -->

    <!-- Banner Area -->
    @foreach ($ading as $ad)
        <div class="#" style="margin-top: 30px;">
            <div class="container">
                <div class="imgbanner imgbanner-2 mt-30">
                    <a href="/products">
                        <img src="{{ asset($ad->ad_1_1110) }}" alt="banner">
                    </a>
                </div>
            </div>
        </div>
    @endforeach
    <!--// Banner Area -->

    <!-- Newarrival, Best seller & Features Product -->
    <div class="ho-section newarrival-bestseller-featured-product mtb-30">
                <div class="container">
                    <div class="section-title-2">
                        <ul class="nav" id="bstab3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="bstab3-area1-tab" data-bs-toggle="tab"
                                    href="#bstab3-area1" role="tab" aria-controls="bstab3-area1"
                                    aria-selected="true">{{ __('frontend.new_arrival') }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="bstab3-ontent">
                        <div class="tab-pane fade show active" id="bstab3-area1" role="tabpanel"
                            aria-labelledby="bstab3-area1-tab">
                            <div class="product-slider new-best-featured-slider slider-navigation-2">


                                <!-- Single Product -->
                                @foreach ($new_arrival_products as $product)
                                    <div class="product-slider-col">
                                        <article class="hoproduct">

                                            <div class="hoproduct-image">
                                                <a class="hoproduct-thumb" href="/details/{{ $product->id }}">
                                                    <img class="hoproduct-frontimage" src="{{ asset($product->photo) }}"
                                                        alt="product image">
                                                    <img class="hoproduct-backimage" src="{{ asset($product->photo) }}"
                                                        alt="product image">
                                                </a>
                                                <ul class="hoproduct-actionbox">
                                                    <li><a href="/cart"><i class="lnr lnr-cart"></i></a></li>
                                                    <li><a href="/details/{{ $product->id }}" class="quickview-trigger"
                                                            data-product-id="{{ $product->id }}"><i
                                                                class="lnr lnr-eye"></i></a></li>
                                                </ul>
                                                <ul class="hoproduct-flags">
                                                    <li class="flag-pack">{{ __('frontend.new') }}</li>
                                                    <li class="flag-discount">-{{ $product->discount_percentage }}%</li>
                                                </ul>
                                            </div>
                                            <div class="hoproduct-content">
                                                @if (Session::get('locale') === 'ar')
                                                    <h5 class="hoproduct-title"><a
                                                            href="/details/{{ $product->id }}">{{ $product->name_ar }}</a>
                                                    </h5>
                                                @else
                                                    <h5 class="hoproduct-title"><a
                                                            href="/details/{{ $product->id }}">{{ $product->name_en }}</a>
                                                    </h5>
                                                @endif
                                                @include('partials.product-stars')
                                                <div class="hoproduct-pricebox">
                                                    <div class="pricebox">
                                                        @if ($product->discount_rate > 0)
                                                            <del class="oldprice">{{ $product->price }} KWD</del>
                                                            <span
                                                                class="price">{{ $product->price_after_discount }} KWD</span>
                                                        @else
                                                            <span class="price">{{ $product->price }} KWD</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Promotional CTA Section -->
            <div class="promo-cta-section">
                <div class="container">
                    <div class="promo-cta-content">
                        <span class="promo-cta-tag">
                            {{ Session::get('locale') === 'ar' ? 'التكنولوجيا الذكية' : 'Smart Technology' }}
                        </span>
                        <h2>
                            {{ Session::get('locale') === 'ar' ? 'طوّر مساحتك بـ' : 'Upgrade Your Space with' }}
                            <span
                                class="accent">{{ Session::get('locale') === 'ar' ? 'الأنظمة الذكية' : 'Smart Systems' }}</span>
                        </h2>
                        <p>
                            {{ Session::get('locale') === 'ar'
                                ? 'من كاميرات المراقبة إلى أنظمة التحكم بالمنزل الذكي — نقدم أرقى التقنيات لمنازل وأعمال الكويت.'
                                : 'From surveillance cameras to smart home control systems — Rain Technology brings premium tech to Kuwait\'s homes and businesses.' }}
                        </p>
                        <div class="promo-cta-buttons">
                            <a href="/products" class="btn-cta-primary">
                                <i class="lnr lnr-store"></i>
                                {{ Session::get('locale') === 'ar' ? 'تسوق الكل' : 'Shop All Products' }}
                            </a>
                            <a href="/contactus" class="btn-cta-outline">
                                <i class="lnr lnr-phone"></i>
                                {{ Session::get('locale') === 'ar' ? 'تواصل معنا' : 'Get in Touch' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--// Promotional CTA Section -->

            <!-- Trusted Brands Strip -->
            <div class="brands-strip">
                <div class="container">
                    <p class="brands-strip-label">
                        {{ Session::get('locale') === 'ar' ? 'علاماتنا التجارية الموثوقة' : 'Trusted Brands We Carry' }}
                    </p>
                    <div class="brands-grid">
                        <span class="brand-pill"><i class="lnr lnr-screen"></i> Samsung</span>
                        <span class="brand-pill"><i class="lnr lnr-camera"></i> Hikvision</span>
                        <span class="brand-pill"><i class="lnr lnr-cog"></i> Honeywell</span>
                        <span class="brand-pill"><i class="lnr lnr-network"></i> TP-Link</span>
                        <span class="brand-pill"><i class="lnr lnr-layers"></i> Dahua</span>
                        <span class="brand-pill"><i class="lnr lnr-apartment"></i> Bosch</span>
                    </div>
                </div>
            </div>
            <!--// Trusted Brands Strip -->

            <!-- WhatsApp Floating Action Button -->
            <a href="https://wa.me/96569908320" class="whatsapp-fab" target="_blank" rel="noopener noreferrer"
                data-tooltip="{{ Session::get('locale') === 'ar' ? 'تواصل معنا' : 'Chat with us' }}"
                aria-label="{{ Session::get('locale') === 'ar' ? 'تواصل عبر واتساب' : 'Chat on WhatsApp' }}">
                <span class="whatsapp-fab-pulse" aria-hidden="true"></span>
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
            </a>
            <!--// WhatsApp FAB -->

            <script>
                (function() {
                    'use strict';

                    function updateCountdown() {
                        var now = new Date();
                        var midnight = new Date();
                        midnight.setHours(23, 59, 59, 999);
                        var diff = Math.max(0, midnight.getTime() - now.getTime());
                        var h = Math.floor(diff / 3600000);
                        var m = Math.floor((diff % 3600000) / 60000);
                        var s = Math.floor((diff % 60000) / 1000);
                        var hEl = document.getElementById('countdown-h');
                        var mEl = document.getElementById('countdown-m');
                        var sEl = document.getElementById('countdown-s');
                        if (hEl) hEl.textContent = String(h).padStart(2, '0');
                        if (mEl) mEl.textContent = String(m).padStart(2, '0');
                        if (sEl) sEl.textContent = String(s).padStart(2, '0');
                    }
                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                })();
            </script>
            <style>
                #homeHeroCarousel {
                    display: block !important;
                }

                #homeHeroCarousel .carousel-item .herobanner-single {
                    display: block;
                }

                #homeHeroCarousel .carousel-control-prev,
                #homeHeroCarousel .carousel-control-next {
                    width: auto;
                    opacity: 1;
                    top: 50%;
                    transform: translateY(-50%);
                }

                #homeHeroCarousel .carousel-control-prev {
                    left: 2%;
                }

                #homeHeroCarousel .carousel-control-next {
                    right: 2%;
                }

                @media (max-width: 575px) {
                    #homeHeroCarousel .carousel-item .herobanner-single {
                        position: relative;
                        min-height: 360px;
                        overflow: hidden;
                    }

                    #homeHeroCarousel .carousel-item .herobanner-single>img {
                        position: absolute;
                        inset: 0;
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }

                    #homeHeroCarousel .herobanner-content {
                        position: absolute !important;
                        top: 50%;
                        left: 0;
                        right: 0;
                        transform: translateY(-50%) !important;
                        max-width: 100%;
                        padding: 20px 18px;
                        z-index: 2;
                        background: transparent !important;
                    }

                    #homeHeroCarousel .carousel-item .herobanner-single::after {
                        background: linear-gradient(180deg,
                                rgba(10, 22, 40, 0.65) 0%,
                                rgba(10, 22, 40, 0.58) 45%,
                                rgba(10, 22, 40, 0.72) 100%) !important;
                        z-index: 1;
                    }

                    #homeHeroCarousel .herobanner-content h1,
                    #homeHeroCarousel .herobanner-content h1 span,
                    #homeHeroCarousel .herobanner-content h4,
                    #homeHeroCarousel .herobanner-content p {
                        color: #fff !important;
                    }

                    #homeHeroCarousel .herobanner-content p {
                        max-width: 100%;
                    }
                }
            </style>
    </div>
@endsection
