@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li><a href="{{ route('customer.account.dashboard') }}">{{ Session::get('locale') === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</a></li>
                <li>{{ Session::get('locale') === 'ar' ? 'طلباتي' : 'My Orders' }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="account-orders-area bg-white ptb-30">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('customer.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="account-orders-content">
                    <h2 class="account-title">{{ Session::get('locale') === 'ar' ? 'طلباتي' : 'My Orders' }}</h2>

                    @if($orders->count() > 0)
                    <div class="orders-list">
                        @foreach($orders as $order)
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-info">
                                    <h4>{{ Session::get('locale') === 'ar' ? 'طلب رقم' : 'Order #' }}{{ $order->id }}</h4>
                                    <p class="order-date">
                                        <i class="lnr lnr-calendar-full"></i>
                                        {{ $order->created_at->format('Y-m-d H:i') }}
                                    </p>
                                </div>
                                <div class="order-status">
                                    <span class="badge badge-{{ $order->order_status == \App\Constants\OrderStatus::DELIVERED ? 'success' : ($order->order_status == \App\Constants\OrderStatus::PENDING ? 'warning' : 'info') }}">
                                        {{ \App\Constants\OrderStatus::label($order->order_status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="order-items">
                                <h5>{{ Session::get('locale') === 'ar' ? 'المنتجات' : 'Products' }}</h5>
                                <ul>
                                    @foreach($order->orderitems as $item)
                                    <li>
                                        <span class="item-name">{{ Session::get('locale') === 'ar' ? $item->product->name_ar : $item->product->name_en }}</span>
                                        <span class="item-quantity">x{{ $item->quantity }}</span>
                                        <span class="item-price">{{ $item->product->price * $item->quantity }} KWD</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="order-footer">
                                <div class="order-total">
                                    <strong>{{ Session::get('locale') === 'ar' ? 'المجموع' : 'Total' }}: {{ $order->total }} KWD</strong>
                                </div>
                                <div class="order-actions">
                                    <a href="{{ route('customer.account.order.details', $order->id) }}" class="btn btn-sm btn-primary">
                                        {{ Session::get('locale') === 'ar' ? 'عرض التفاصيل' : 'View Details' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper mt-30">
                        {{ $orders->links() }}
                    </div>
                    @else
                    <div class="no-orders text-center py-5">
                        <i class="lnr lnr-cart" style="font-size: 64px; color: #ccc;"></i>
                        <h3 class="mt-3">{{ Session::get('locale') === 'ar' ? 'لا توجد طلبات' : 'No Orders Found' }}</h3>
                        <p class="text-muted">{{ Session::get('locale') === 'ar' ? 'لم تقم بأي طلبات بعد' : 'You haven\'t placed any orders yet' }}</p>
                        <a href="/products" class="btn btn-primary mt-3">
                            {{ Session::get('locale') === 'ar' ? 'ابدأ التسوق' : 'Start Shopping' }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #ddd;
}

.order-info h4 {
    margin: 0 0 5px 0;
    font-size: 18px;
}

.order-date {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.order-items {
    margin-bottom: 15px;
}

.order-items h5 {
    font-size: 16px;
    margin-bottom: 10px;
}

.order-items ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.order-items li {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #ddd;
}

.badge {
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #28a745;
    color: #fff;
}

.badge-warning {
    background: #ffc107;
    color: #000;
}

.badge-info {
    background: #17a2b8;
    color: #fff;
}
</style>
@endsection
