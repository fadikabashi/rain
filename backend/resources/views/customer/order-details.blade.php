@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li><a href="{{ route('customer.account.dashboard') }}">{{ Session::get('locale') === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</a></li>
                <li><a href="{{ route('customer.account.orders') }}">{{ Session::get('locale') === 'ar' ? 'طلباتي' : 'My Orders' }}</a></li>
                <li>{{ Session::get('locale') === 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="order-details-area bg-white ptb-30">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('customer.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="order-details-content">
                    <div class="order-header-details">
                        <h2>{{ Session::get('locale') === 'ar' ? 'طلب رقم' : 'Order #' }}{{ $order->id }}</h2>
                        <span class="badge badge-{{ $order->order_status == \App\Constants\OrderStatus::DELIVERED ? 'success' : ($order->order_status == \App\Constants\OrderStatus::PENDING ? 'warning' : 'info') }}">
                            {{ \App\Constants\OrderStatus::label($order->order_status) }}
                        </span>
                    </div>

                    <div class="row mt-30">
                        <!-- Order Information -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <h4>{{ Session::get('locale') === 'ar' ? 'معلومات الطلب' : 'Order Information' }}</h4>
                                <table class="info-table">
                                    <tr>
                                        <td>{{ Session::get('locale') === 'ar' ? 'رقم الطلب' : 'Order Number' }}</td>
                                        <td>#{{ $order->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ Session::get('locale') === 'ar' ? 'تاريخ الطلب' : 'Order Date' }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ Session::get('locale') === 'ar' ? 'حالة الطلب' : 'Order Status' }}</td>
                                        <td>{{ \App\Constants\OrderStatus::label($order->order_status) }}</td>
                                    </tr>
                                    @if($order->coupon)
                                    <tr>
                                        <td>{{ Session::get('locale') === 'ar' ? 'كود الخصم' : 'Coupon Code' }}</td>
                                        <td>{{ $order->coupon->id }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- Delivery Information -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <h4>{{ Session::get('locale') === 'ar' ? 'معلومات التسليم' : 'Delivery Information' }}</h4>
                                <table class="info-table">
                                    <tr>
                                        <td>{{ Session::get('locale') === 'ar' ? 'الاسم' : 'Name' }}</td>
                                        <td>{{ $order->costumer_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ Session::get('locale') === 'ar' ? 'رقم الهاتف' : 'Phone' }}</td>
                                        <td>{{ $order->costumer_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ Session::get('locale') === 'ar' ? 'العنوان' : 'Address' }}</td>
                                        <td>{{ $order->address }}</td>
                                    </tr>
                                    @if($order->note)
                                    <tr>
                                        <td>{{ Session::get('locale') === 'ar' ? 'ملاحظات' : 'Notes' }}</td>
                                        <td>{{ $order->note }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items-details mt-30">
                        <h4>{{ Session::get('locale') === 'ar' ? 'عناصر الطلب' : 'Order Items' }}</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ Session::get('locale') === 'ar' ? 'المنتج' : 'Product' }}</th>
                                        <th>{{ Session::get('locale') === 'ar' ? 'الكمية' : 'Quantity' }}</th>
                                        <th>{{ Session::get('locale') === 'ar' ? 'السعر' : 'Price' }}</th>
                                        <th>{{ Session::get('locale') === 'ar' ? 'المجموع' : 'Total' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderitems as $item)
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <img src="{{ asset($item->product->photo) }}" alt="{{ Session::get('locale') === 'ar' ? $item->product->name_ar : $item->product->name_en }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                <span>{{ Session::get('locale') === 'ar' ? $item->product->name_ar : $item->product->name_en }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->product->price }} KWD</td>
                                        <td>{{ $item->product->price * $item->quantity }} KWD</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>{{ Session::get('locale') === 'ar' ? 'المجموع الكلي' : 'Total Amount' }}</strong></td>
                                        <td><strong>{{ $order->total }} KWD</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="order-actions mt-30">
                        <a href="{{ route('customer.account.orders') }}" class="btn btn-outline">
                            {{ Session::get('locale') === 'ar' ? 'العودة إلى الطلبات' : 'Back to Orders' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-header-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 2px solid #eee;
}

.info-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.info-card h4 {
    margin-bottom: 15px;
    font-size: 18px;
}

.info-table {
    width: 100%;
}

.info-table td {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.info-table td:first-child {
    font-weight: 600;
    color: #666;
    width: 40%;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 10px;
}
</style>
@endsection
