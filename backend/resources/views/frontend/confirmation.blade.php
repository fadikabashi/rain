@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li><a href="/products">{{__('frontend.shop')}}</a></li>
                <li>{{__("frontend.order_confirmation")}}</li>
            </ul>
        </div>
    </div>
</div>

<div class="checkout-area bg-white ptb-30">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Success Message -->
                <div class="alert alert-success text-center mb-30" role="alert">
                    <h2 class="mb-2">
                        <i class="lnr lnr-checkmark-circle" style="font-size: 48px; color: #28a745;"></i>
                    </h2>
                    <h3 class="mb-2">{{__('frontend.order_confirmed')}}</h3>
                    <p class="mb-0">{{__('frontend.thank_you_message')}}</p>
                </div>

                <!-- Order Details -->
                <div class="order-confirmation-box">
                    <div class="row">
                        <!-- Order Information -->
                        <div class="col-lg-6 col-md-6 col-12 mb-30">
                            <div class="ho-box">
                                <h3 class="small-title mb-20">{{__('frontend.order_information')}}</h3>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="40%">{{__('frontend.order_number')}}</th>
                                            <td><strong>#{{ $order->id }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>{{__('frontend.order_date')}}</th>
                                            <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('frontend.order_status')}}</th>
                                            <td>
                                                <span class="badge badge-{{ $order->order_status == 1 ? 'success' : 'warning' }}">
                                                    @if($order->order_status == 1)
                                                        {{__('frontend.completed')}}
                                                    @elseif($order->order_status == 2)
                                                        {{__('frontend.processing')}}
                                                    @else
                                                        {{__('frontend.pending')}}
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{__('frontend.total_amount')}}</th>
                                            <td><strong>{{ $order->total }} KWD</strong></td>
                                        </tr>
                                        @if($order->coupon)
                                        <tr>
                                            <th>{{__('frontend.coupon_code')}}</th>
                                            <td><span class="text-success">{{ $order->coupon->id }}</span></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="col-lg-6 col-md-6 col-12 mb-30">
                            <div class="ho-box">
                                <h3 class="small-title mb-20">{{__('frontend.customer_information')}}</h3>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="40%">{{__('frontend.name')}}</th>
                                            <td>{{ $order->costumer_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('frontend.phone')}}</th>
                                            <td>{{ $order->costumer_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('frontend.address')}}</th>
                                            <td>{{ $order->address }}</td>
                                        </tr>
                                        @if($order->note)
                                        <tr>
                                            <th>{{__('frontend.note')}}</th>
                                            <td>{{ $order->note }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="row">
                        <div class="col-12 mb-30">
                            <div class="ho-box">
                                <h3 class="small-title mb-20">{{__('frontend.order_items')}}</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{__('frontend.product')}}</th>
                                                <th class="text-center">{{__('frontend.quantity')}}</th>
                                                <th class="text-right">{{__('frontend.price')}}</th>
                                                <th class="text-right">{{__('frontend.subtotal')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->orderitems as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product && $item->product->photo)
                                                        <img src="{{ asset($item->product->photo) }}" 
                                                             alt="{{ $item->product->name_en }}" 
                                                             style="width: 60px; height: 60px; object-fit: cover; margin-right: 15px; border-radius: 4px;">
                                                        @endif
                                                        <div>
                                                            <strong>
                                                                {{ Session::get('locale') === 'ar' ? ($item->product->name_ar ?? __('frontend.not_available')) : ($item->product->name_en ?? __('frontend.not_available')) }}
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-right">
                                                    @if($item->product)
                                                        {{ $item->product->price }} KWD
                                                    @else
                                                        {{__('frontend.not_available')}}
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    <strong>
                                                        @if($item->product)
                                                            {{ $item->product->price * $item->quantity }} KWD
                                                        @else
                                                            {{__('frontend.not_available')}}
                                                        @endif
                                                    </strong>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">{{__('frontend.subtotal')}}</th>
                                                <th class="text-right">
                                                    {{ $order->orderitems->sum(function($item) { 
                                                        return $item->product ? ($item->product->price * $item->quantity) : 0; 
                                                    }) }} KWD
                                                </th>
                                            </tr>
                                            @if($order->coupon)
                                            <tr>
                                                <th colspan="3" class="text-right">{{__('frontend.coupon_discount')}}</th>
                                                <th class="text-right text-success">-{{ $order->coupon->amount }} KWD</th>
                                            </tr>
                                            @endif
                                            <tr class="total-row">
                                                <th colspan="3" class="text-right">{{__('frontend.total')}}</th>
                                                <th class="text-right"><strong>{{ $order->total }} KWD</strong></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="ho-buttongroup">
                                <a href="/products" class="ho-button ho-button-sm">
                                    <span>{{__('frontend.continue_shopping')}}</span>
                                </a>
                                <a href="/" class="ho-button ho-button-sm ho-button-dark">
                                    <span>{{__('frontend.back_to_home')}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-confirmation-box {
    margin-top: 30px;
}

.ho-box {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    margin-bottom: 20px;
}

.ho-box h3 {
    color: #333;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.total-row {
    background-color: #e9ecef;
    font-weight: bold;
}

.total-row th {
    background-color: #e9ecef;
    font-size: 18px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    padding: 30px;
    border-radius: 8px;
}

.badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.ho-buttongroup {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .ho-box {
        padding: 15px;
    }
    
    .ho-buttongroup {
        flex-direction: column;
    }
    
    .ho-buttongroup .ho-button {
        width: 100%;
    }
}
</style>
@endsection
