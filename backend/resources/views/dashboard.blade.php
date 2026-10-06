@extends('layouts.app')
@section('title', 'الرئيسية')
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0"> الطلبات </h2>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-8 col-md-6 col-12">
    <div class="card card-statistics">
        <div class="card-header">
            <h4 class="card-title"> احصائيات </h4>    
        </div>
        <div class="card-body statistics-body">
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                    <div class="media">
                        <div class="avatar bg-primary mr-2">
                            <div class="avatar-content">
                                <i data-feather="trending-up" class="avatar-icon font-medium-2 fa fa-bell"></i>
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0">{{$order_count}}</h4>
                            <p class="card-text font-small-3 mb-0">الطلبات</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                    <div class="media">
                        <div class="avatar bg-danger mr-2">
                            <div class="avatar-content">
                                <i data-feather="user" class="avatar-icon font-medium-2 fa fa-envelope"></i>
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0">{{$new_order}}</h4>
                            <p class="card-text font-small-3 mb-0">غير المعالجة</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                    <div class="media">
                        <div class="avatar bg-warning mr-2">
                            <div class="avatar-content">
                                <i data-feather="box" class="avatar-icon font-medium-2 fa fa-check-square"></i>
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0">{{$received_order}}</h4>
                            <p class="card-text font-small-3 mb-0">المعالجة</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                    <div class="media">
                        <div class="avatar bg-success mr-2">
                            <div class="avatar-content">
                                <i data-feather="trending-up" class="trending-up  font-medium-2 fa fa-truck"></i>
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0">{{$delivered_order}}</h4>
                            <p class="card-text font-small-3 mb-0">تم التوصيل</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">المنتجات </h2>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-xl-2 col-md-4 col-sm-6">
    <div class="card text-center">
        <div class="card-body">
            <div class="avatar bg-info p-50 mb-1">
                <div class="avatar-content">
                    <i data-feather="eye" class="font-medium-5 fa fa-shopping-bag"></i>
                </div>
            </div>
            <h2 class="font-weight-bolder">{{$products_counter}}</h2>
            <p class="card-text">المنتجات</p>
        </div>
    </div>
</div>
<div class="col-xl-2 col-md-4 col-sm-6">
    <div class="card text-center">
        <div class="card-body">
            <div class="avatar bg-warning p-50 mb-1">
                <div class="avatar-content">
                    <i data-feather="message-square" class="font-medium-5 fa fa-check"></i>
                </div>
            </div>
            <h2 class="font-weight-bolder">{{$in_stack}}</h2>
            <p class="card-text">متوفرة</p>
        </div>
    </div>
</div>
<div class="col-xl-2 col-md-4 col-sm-6">
    <div class="card text-center">
        <div class="card-body">
            <div class="avatar bg-danger p-50 mb-1">
                <div class="avatar-content">
                    <i data-feather="shopping-bag" class="font-medium-5 fa fa-remove"></i>
                </div>
            </div>
            <h2 class="font-weight-bolder">{{$out_off_stack}}</h2>
            <p class="card-text">غير متوفرة </p>
        </div>
    </div>
</div>

</div>

<div class="row mt-2">
    <div class="col-lg-8 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">اتجاه الطلبات والإيرادات (آخر 6 أشهر)</h4>
            </div>
            <div class="card-body">
                <div id="orders-revenue-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">حالات الطلبات</h4>
            </div>
            <div class="card-body">
                <div id="order-status-chart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">حالة المخزون</h4>
            </div>
            <div class="card-body">
                <div id="stock-status-chart"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scriptjs')
<script>
    (function () {
        const chartData = @json($chartData ?? []);

        const monthLabels = chartData.monthly_labels || [];
        const monthlyOrders = chartData.monthly_orders || [];
        const monthlyRevenue = chartData.monthly_revenue || [];
        const orderStatus = chartData.order_status || {};
        const productStock = chartData.product_stock || {};

        const ordersRevenueEl = document.querySelector('#orders-revenue-chart');
        if (ordersRevenueEl) {
            const ordersRevenueChart = new ApexCharts(ordersRevenueEl, {
                chart: {
                    type: 'line',
                    height: 320,
                    toolbar: { show: false }
                },
                stroke: { width: [3, 3], curve: 'smooth' },
                series: [
                    { name: 'عدد الطلبات', type: 'column', data: monthlyOrders },
                    { name: 'الإيرادات (KWD)', type: 'line', data: monthlyRevenue }
                ],
                xaxis: { categories: monthLabels },
                yaxis: [
                    { title: { text: 'الطلبات' } },
                    { opposite: true, title: { text: 'الإيرادات' } }
                ],
                colors: ['#7367f0', '#28c76f']
            });
            ordersRevenueChart.render();
        }

        const statusEl = document.querySelector('#order-status-chart');
        if (statusEl) {
            const orderStatusChart = new ApexCharts(statusEl, {
                chart: { type: 'donut', height: 320 },
                labels: ['جديد', 'قيد المعالجة', 'تم التوصيل'],
                series: [
                    Number(orderStatus.new || 0),
                    Number(orderStatus.received || 0),
                    Number(orderStatus.delivered || 0)
                ],
                colors: ['#ea5455', '#ff9f43', '#28c76f'],
                legend: { position: 'bottom' }
            });
            orderStatusChart.render();
        }

        const stockEl = document.querySelector('#stock-status-chart');
        if (stockEl) {
            const stockStatusChart = new ApexCharts(stockEl, {
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                plotOptions: { bar: { distributed: true, borderRadius: 4 } },
                series: [{
                    name: 'المنتجات',
                    data: [
                        Number(productStock.in_stock || 0),
                        Number(productStock.out_of_stock || 0),
                        Number(productStock.low_quantity || 0)
                    ]
                }],
                xaxis: { categories: ['متوفر', 'غير متوفر', 'كمية منخفضة'] },
                colors: ['#28c76f', '#ea5455', '#ff9f43'],
                legend: { show: false }
            });
            stockStatusChart.render();
        }
    })();
</script>
@endsection
