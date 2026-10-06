@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li>{{ Session::get('locale') === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="account-dashboard-area bg-white ptb-30">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('customer.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="account-dashboard-content">
                    <h2 class="account-title">{{ Session::get('locale') === 'ar' ? 'مرحباً، ' : 'Welcome, ' }}{{ $user->name }}</h2>

                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mt-30">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="lnr lnr-cart"></i>
                                </div>
                                <div class="stat-content">
                                    <h3>{{ $orderStats['total'] }}</h3>
                                    <p>{{ Session::get('locale') === 'ar' ? 'إجمالي الطلبات' : 'Total Orders' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="lnr lnr-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <h3>{{ $orderStats['pending'] }}</h3>
                                    <p>{{ Session::get('locale') === 'ar' ? 'طلبات قيد الانتظار' : 'Pending Orders' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="lnr lnr-checkmark-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <h3>{{ $orderStats['delivered'] }}</h3>
                                    <p>{{ Session::get('locale') === 'ar' ? 'طلبات تم التسليم' : 'Delivered Orders' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="recent-orders mt-30">
                        <h3 class="section-title">{{ Session::get('locale') === 'ar' ? 'الطلبات الأخيرة' : 'Recent Orders' }}</h3>
                        
                        @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ Session::get('locale') === 'ar' ? 'رقم الطلب' : 'Order #' }}</th>
                                        <th>{{ Session::get('locale') === 'ar' ? 'التاريخ' : 'Date' }}</th>
                                        <th>{{ Session::get('locale') === 'ar' ? 'المجموع' : 'Total' }}</th>
                                        <th>{{ Session::get('locale') === 'ar' ? 'الحالة' : 'Status' }}</th>
                                        <th>{{ Session::get('locale') === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $order->total }} KWD</td>
                                        <td>
                                            <span class="badge badge-{{ $order->order_status == \App\Constants\OrderStatus::DELIVERED ? 'success' : ($order->order_status == \App\Constants\OrderStatus::PENDING ? 'warning' : 'info') }}">
                                                {{ \App\Constants\OrderStatus::label($order->order_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.account.order.details', $order->id) }}" class="btn btn-sm btn-primary">
                                                {{ Session::get('locale') === 'ar' ? 'عرض' : 'View' }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('customer.account.orders') }}" class="btn btn-outline">
                                {{ Session::get('locale') === 'ar' ? 'عرض جميع الطلبات' : 'View All Orders' }}
                            </a>
                        </div>
                        @else
                        <div class="no-orders text-center py-5">
                            <i class="lnr lnr-cart" style="font-size: 64px; color: #ccc;"></i>
                            <p class="mt-3">{{ Session::get('locale') === 'ar' ? 'لا توجد طلبات بعد' : 'No orders yet' }}</p>
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
</div>

<style>
.stat-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.stat-icon {
    font-size: 40px;
    color: #007bff;
}

.stat-content h3 {
    font-size: 32px;
    margin: 0;
    font-weight: 600;
}

.stat-content p {
    margin: 5px 0 0 0;
    color: #666;
}

.account-title {
    font-size: 28px;
    margin-bottom: 20px;
}

.section-title {
    font-size: 20px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
}
</style>
@endsection
