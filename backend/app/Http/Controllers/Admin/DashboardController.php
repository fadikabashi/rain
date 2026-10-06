<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id','Desc')->get();
        $low_quantity=Product::where('quantity','<', 5)->count();
        $products_counter= $products->count();
        $out_off_stack=Product::where('is_available', 0)->count();
        $in_stack= Product::where('is_available', 1)->count();
        $orders = Order::orderBy('id','Desc')->get();
        $order_count= $orders->count();
        $new_order=Order::where('order_status',0)->count();
        $received_order=Order::where('order_status',1)->count();
        $delivered_order=Order::where('order_status',2)->count();

        // Build chart data for the last 6 months
        $months = collect(range(5, 0))->map(function ($offset) {
            return Carbon::now()->subMonths($offset);
        });

        $ordersByMonth = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total_orders, COALESCE(SUM(total), 0) as total_revenue')
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function ($row) {
                return sprintf('%04d-%02d', $row->year, $row->month);
            });

        $monthlyLabels = [];
        $monthlyOrders = [];
        $monthlyRevenue = [];

        foreach ($months as $monthDate) {
            $monthKey = $monthDate->format('Y-m');
            $row = $ordersByMonth->get($monthKey);

            $monthlyLabels[] = $monthDate->format('M Y');
            $monthlyOrders[] = (int) ($row->total_orders ?? 0);
            $monthlyRevenue[] = (float) ($row->total_revenue ?? 0);
        }

        $chartData = [
            'monthly_labels' => $monthlyLabels,
            'monthly_orders' => $monthlyOrders,
            'monthly_revenue' => $monthlyRevenue,
            'order_status' => [
                'new' => $new_order,
                'received' => $received_order,
                'delivered' => $delivered_order,
            ],
            'product_stock' => [
                'in_stock' => $in_stack,
                'out_of_stock' => $out_off_stack,
                'low_quantity' => $low_quantity,
            ],
        ];

        return view('dashboard')
        ->with('products',$products)
        ->with('products_counter',$products_counter)
        ->with('low_quantity', $low_quantity)
        ->with('out_off_stack', $out_off_stack)
        ->with('in_stack', $in_stack)
        ->with('orders',$orders)
        ->with('order_count',$order_count)
        ->with('new_order',$new_order)
        ->with('received_order',$received_order)
        ->with('delivered_order',$delivered_order)
        ->with('chartData', $chartData)
        ;
    }
}
