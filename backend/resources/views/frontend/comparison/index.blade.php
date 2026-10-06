@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))

@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li>{{__('frontend.product_comparison')}}</li>
            </ul>
        </div>
    </div>
</div>

<div class="comparison-area bg-white ptb-30">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="comparison-header mb-4">
                    <h2>{{__('frontend.product_comparison')}}</h2>
                    @if($products->count() > 0)
                    <button type="button" class="btn btn-danger btn-sm" id="clearComparisonBtn">
                        <i class="lnr lnr-trash"></i> {{__('frontend.clear_comparison')}}
                    </button>
                    @endif
                </div>

                @if($products->count() === 0)
                <div class="empty-comparison text-center py-5">
                    <i class="lnr lnr-layers" style="font-size: 64px; color: #ccc;"></i>
                    <h3 class="mt-3">{{__('frontend.no_products_to_compare')}}</h3>
                    <p class="text-muted">{{__('frontend.add_products_to_compare')}}</p>
                    <a href="{{ route('products') }}" class="btn btn-primary mt-3">
                        {{__('frontend.browse_products')}}
                    </a>
                </div>
                @else
                <div class="comparison-table-wrapper">
                    <div class="table-responsive">
                        <table class="table table-bordered comparison-table">
                            <thead>
                                <tr>
                                    <th>{{__('frontend.product')}}</th>
                                    @foreach($products as $product)
                                    <th class="text-center">
                                        <div class="comparison-product-header">
                                            <img src="{{ asset($product->photo) }}" alt="{{ Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en }}" 
                                                 style="max-width: 150px; height: auto; margin-bottom: 10px;">
                                            <h5>{{ Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en }}</h5>
                                            <button type="button" class="btn btn-sm btn-danger remove-from-comparison" 
                                                    data-product-id="{{ $product->id }}">
                                                <i class="lnr lnr-cross"></i> {{__('frontend.remove')}}
                                            </button>
                                        </div>
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>{{__('frontend.price')}}</strong></td>
                                    @foreach($products as $product)
                                    <td class="text-center">
                                        @if($product->discount > 0)
                                        <del class="text-muted">{{ $product->price }} KWD</del><br>
                                        <span class="text-primary font-weight-bold">{{ $product->price * (1 - $product->discount) }} KWD</span>
                                        @else
                                        <span class="font-weight-bold">{{ $product->price }} KWD</span>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>{{__('frontend.availability')}}</strong></td>
                                    @foreach($products as $product)
                                    <td class="text-center">
                                        @if($product->is_available && $product->quantity > 0)
                                        <span class="badge badge-success">{{__('frontend.in_stock')}}</span>
                                        @else
                                        <span class="badge badge-danger">{{__('frontend.out_of_stock')}}</span>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>{{__('frontend.quantity')}}</strong></td>
                                    @foreach($products as $product)
                                    <td class="text-center">{{ $product->quantity ?? 0 }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>{{__('frontend.category')}}</strong></td>
                                    @foreach($products as $product)
                                    <td class="text-center">
                                        {{ Session::get('locale') === 'ar' ? ($product->category->name_ar ?? '-') : ($product->category->name_en ?? '-') }}
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>{{__('frontend.manufacturer')}}</strong></td>
                                    @foreach($products as $product)
                                    <td class="text-center">
                                        {{ Session::get('locale') === 'ar' ? ($product->manfacturer->name_ar ?? '-') : ($product->manfacturer->name_en ?? '-') }}
                                    </td>
                                    @endforeach
                                </tr>
                                @if($products->first()->specifications && $products->first()->specifications->count() > 0)
                                @foreach($products->first()->specifications as $spec)
                                <tr>
                                    <td><strong>{{ Session::get('locale') === 'ar' ? $spec->spec_name_ar : $spec->spec_name_en }}</strong></td>
                                    @foreach($products as $product)
                                    <td class="text-center">
                                        @php
                                            $productSpec = $product->specifications->where('id', $spec->id)->first();
                                        @endphp
                                        {{ $productSpec ? (Session::get('locale') === 'ar' ? $productSpec->spec_value_ar : $productSpec->spec_value_en) : '-' }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                                @endif
                                <tr>
                                    <td><strong>{{__('frontend.actions')}}</strong></td>
                                    @foreach($products as $product)
                                    <td class="text-center">
                                        <a href="{{ route('details', $product->id) }}" class="btn btn-sm btn-primary">
                                            {{__('frontend.view_details')}}
                                        </a>
                                        @if($product->is_available && $product->quantity > 0)
                                        @livewire('add-to-cart-component', [
                                            'productId' => $product->id,
                                            'productName' => Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en,
                                            'productPrice' => $product->price,
                                            'productPhoto' => $product->photo,
                                            'compact' => true
                                        ], key('comparison-add-to-cart-' . $product->id))
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    // Clear comparison button
    const clearBtn = document.getElementById('clearComparisonBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (confirm('{{ __("frontend.confirm_clear_comparison") }}')) {
                fetch('{{ route("comparison.clear") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Clear response status:', response.status);
                    if (response.status === 200 || response.status === 302) {
                        return response.json().catch(() => {
                            // If not JSON, it's a redirect - reload page
                            window.location.reload();
                            return { success: true };
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Clear response data:', data);
                    if (data && data.success) {
                        window.location.reload();
                    } else if (data && data.message) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Clear error:', error);
                    // Reload page anyway
                    window.location.reload();
                });
            }
        });
    }

    // Remove product from comparison
    document.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-from-comparison');
        if (removeBtn) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = removeBtn.getAttribute('data-product-id');
            if (!productId) {
                console.error('Product ID not found');
                return;
            }
            
            const url = '{{ route("comparison.remove", ":id") }}'.replace(':id', productId);
            console.log('Removing product:', productId, 'URL:', url);
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Remove response status:', response.status);
                if (response.status === 200 || response.status === 302) {
                    return response.json().catch(() => {
                        // If not JSON, it's a redirect - reload page
                        window.location.reload();
                        return { success: true };
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Remove response data:', data);
                if (data && data.success) {
                    window.location.reload();
                } else if (data && data.message) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Remove error:', error);
                // Reload page anyway
                window.location.reload();
            });
        }
    });
});
</script>
@endsection
