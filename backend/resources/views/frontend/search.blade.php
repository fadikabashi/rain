@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li>{{__('frontend.search_results')}}</li>
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
        <!-- Search Header -->
        <div class="search-header mt-30 mb-30">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="search-title">
                        @if($query)
                            {{__('frontend.search_results_for')}}: "<strong>{{ $query }}</strong>"
                        @else
                            {{__('frontend.search_results')}}
                        @endif
                    </h2>
                    <p class="search-count">
                        {{__('frontend.found')}} <strong>{{ $products_count }}</strong> 
                        {{ $products_count == 1 ? __('frontend.product') : __('frontend.products') }}
                    </p>
                </div>
                <div class="col-lg-4">
                    <!-- Search Form -->
                    <form action="{{ route('search') }}" method="GET" class="search-form-inline">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" 
                                   placeholder="{{__('frontend.search_placeholder')}}" 
                                   value="{{ $query }}" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="lnr lnr-magnifier"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($products_count > 0)
        <!-- Advanced Filters Sidebar -->
        <div class="row mt-30">
            <div class="col-lg-3 col-md-4">
                <div class="shop-sidebar">
                    <div class="sidebar-widget">
                        <h3 class="widget-title">{{__('frontend.filters')}}</h3>
                        <form action="{{ route('search') }}" method="GET" id="filterForm">
                            <input type="hidden" name="q" value="{{ $query }}">
                            
                            <!-- Category Filter -->
                            <div class="filter-group mb-3">
                                <label class="filter-label">{{__('frontend.category')}}</label>
                                <select name="category" class="form-control filter-select">
                                    <option value="0">{{__('frontend.all_categories')}}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? null) == $category->id ? 'selected' : '' }}>
                                        {{ Session::get('locale') === 'ar' ? $category->name_ar : $category->name_en }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Manufacturer Filter -->
                            <div class="filter-group mb-3">
                                <label class="filter-label">{{__('frontend.manufacturer')}}</label>
                                <select name="manufacturer" class="form-control filter-select">
                                    <option value="0">{{__('frontend.all_manufacturers')}}</option>
                                    @foreach($manufacturers as $manufacturer)
                                    <option value="{{ $manufacturer->id }}" {{ ($filters['manufacturer_id'] ?? null) == $manufacturer->id ? 'selected' : '' }}>
                                        {{ Session::get('locale') === 'ar' ? $manufacturer->name_ar : $manufacturer->name_en }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Type Filter -->
                            <div class="filter-group mb-3">
                                <label class="filter-label">{{__('frontend.type')}}</label>
                                <select name="type" class="form-control filter-select">
                                    <option value="0">{{__('frontend.all_types')}}</option>
                                    @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ ($filters['type_id'] ?? null) == $type->id ? 'selected' : '' }}>
                                        {{ Session::get('locale') === 'ar' ? $type->name_ar : $type->name_en }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Price Range Filter -->
                            <div class="filter-group mb-3">
                                <label class="filter-label">{{__('frontend.price_range')}} (KWD)</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" name="min_price" class="form-control" 
                                               placeholder="{{__('frontend.min')}}" 
                                               value="{{ $filters['min_price'] ?? '' }}" 
                                               min="0" step="0.01">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="max_price" class="form-control" 
                                               placeholder="{{__('frontend.max')}}" 
                                               value="{{ $filters['max_price'] ?? '' }}" 
                                               min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Availability Filter -->
                            <div class="filter-group mb-3">
                                <label class="filter-label">{{__('frontend.availability')}}</label>
                                <select name="availability" class="form-control filter-select">
                                    <option value="">{{__('frontend.all')}}</option>
                                    <option value="in_stock" {{ ($filters['availability'] ?? '') == 'in_stock' ? 'selected' : '' }}>{{__('frontend.in_stock')}}</option>
                                    <option value="low_stock" {{ ($filters['availability'] ?? '') == 'low_stock' ? 'selected' : '' }}>{{__('frontend.low_stock')}}</option>
                                    <option value="out_of_stock" {{ ($filters['availability'] ?? '') == 'out_of_stock' ? 'selected' : '' }}>{{__('frontend.out_of_stock')}}</option>
                                </select>
                            </div>
                            
                            <!-- Sort Filter -->
                            <div class="filter-group mb-3">
                                <label class="filter-label">{{__('frontend.sort_by')}}</label>
                                <select name="sort" class="form-control filter-select">
                                    <option value="newest" {{ ($filters['sort'] ?? 'newest') == 'newest' ? 'selected' : '' }}>{{__('frontend.newest')}}</option>
                                    <option value="price_low_high" {{ ($filters['sort'] ?? '') == 'price_low_high' ? 'selected' : '' }}>{{__('frontend.price_low_to_high')}}</option>
                                    <option value="price_high_low" {{ ($filters['sort'] ?? '') == 'price_high_low' ? 'selected' : '' }}>{{__('frontend.price_high_to_low')}}</option>
                                    <option value="name_asc" {{ ($filters['sort'] ?? '') == 'name_asc' ? 'selected' : '' }}>{{__('frontend.name_a_z')}}</option>
                                    <option value="name_desc" {{ ($filters['sort'] ?? '') == 'name_desc' ? 'selected' : '' }}>{{__('frontend.name_z_a')}}</option>
                                    <option value="discount" {{ ($filters['sort'] ?? '') == 'discount' ? 'selected' : '' }}>{{__('frontend.best_discount')}}</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">{{__('frontend.apply_filters')}}</button>
                            <a href="{{ route('search', ['q' => $query]) }}" class="btn btn-secondary btn-block mt-2">{{__('frontend.clear_filters')}}</a>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-9 col-md-8">
        <!-- Products Grid -->
        <div class="shop-filters">
            <div class="shop-filters-viewmode">
                <button class="is-active" data-view="grid"><i class="ion ion-ios-keypad"></i></button>
                <button data-view="list"><i class="ion ion-ios-list"></i></button>
            </div>
            <span class="shop-filters-viewitemcount">
                {{__('frontend.there_are')}} {{$products_count}} {{__('frontend.products')}}
            </span>
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
                                    ], key('add-to-cart-search-' . $product->id))
                                </li>
                                <li><a href="/details/{{$product->id}}" class="quickview-trigger" data-product-id="{{$product->id}}"><i class="lnr lnr-eye"></i></a></li>
                                <li>
                                    <a href="#" class="wishlist-toggle" data-product-id="{{ $product->id }}" title="{{ Session::get('locale') === 'ar' ? 'إضافة إلى قائمة الأمنيات' : 'Add to Wishlist' }}">
                                        <i class="lnr lnr-heart"></i>
                                    </a>
                                </li>
                            </ul>
                            <ul class="hoproduct-flags">
                                @if($product->discount_rate > 0)
                                <li class="flag-discount">-{{$product->discount_percentage}}%</li>
                                @endif
                                @if($product->is_available && $product->quantity > 0)
                                <li class="flag-new">{{__('frontend.in_stock')}}</li>
                                @else
                                <li class="flag-sale">{{__('frontend.out_of_stock')}}</li>
                                @endif
                            </ul>
                        </div>
                        <div class="hoproduct-content">
                            <h5 class="hoproduct-title">
                                <a href="/details/{{$product->id}}">
                                    {{ Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en }}
                                </a>
                            </h5>
                            <div class="hoproduct-pricebox">
                                @if($product->discount_rate > 0)
                                <span class="price">{{ $product->price_after_discount }} KWD</span>
                                <span class="oldprice">{{ $product->price }} KWD</span>
                                @else
                                <span class="price">{{ $product->price }} KWD</span>
                                @endif
                            </div>
                        </div>
                    </article>
                    <!--// Single Product -->
                </div>
                @endforeach
            </div>
        </div>
            </div>
        </div>
        @else
        <!-- No Results -->
        <div class="no-results text-center py-5">
            <div class="no-results-icon mb-3">
                <i class="lnr lnr-magnifier" style="font-size: 64px; color: #ccc;"></i>
            </div>
            <h3>{{__('frontend.no_results_found')}}</h3>
            <p class="text-muted">{{__('frontend.try_different_keywords')}}</p>
            <a href="/products" class="ho-button ho-button-sm mt-3">
                <span>{{__('frontend.browse_all_products')}}</span>
            </a>
        </div>
        @endif
    </div>
</div>

<style>
.shop-sidebar {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.sidebar-widget {
    margin-bottom: 20px;
}

.widget-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

.filter-group {
    margin-bottom: 15px;
}

.filter-label {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
    color: #555;
    font-size: 14px;
}

.filter-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.filter-select:focus {
    border-color: #007bff;
    outline: none;
}

@media (max-width: 768px) {
    .shop-sidebar {
        margin-bottom: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit on filter change (optional - can be removed if manual submit preferred)
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Optional: auto-submit on change
            // document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endsection
