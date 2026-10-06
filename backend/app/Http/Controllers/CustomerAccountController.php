<?php

namespace App\Http\Controllers;

use App\Constants\OrderStatus;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\QuoteRequestService;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAccountController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected WishlistService $wishlistService,
        protected QuoteRequestService $quoteRequestService
    ) {
        $this->middleware('auth');
    }

    /**
     * Display customer dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['orderitems.product', 'coupon'])
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        // Get order statistics
        $orderStats = [
            'total' => Order::where('user_id', $user->id)->count(),
            'pending' => Order::where('user_id', $user->id)
                ->where('order_status', OrderStatus::PENDING)->count(),
            'delivered' => Order::where('user_id', $user->id)
                ->where('order_status', OrderStatus::DELIVERED)->count(),
        ];

        // Get wishlist count
        $wishlistCount = $this->wishlistService->getCount();

        return view('customer.dashboard', compact('user', 'recentOrders', 'orderStats', 'wishlistCount'));
    }

    /**
     * Display customer orders.
     *
     * @return \Illuminate\View\View
     */
    public function orders()
    {
        $user = Auth::user();
        
        $orders = Order::where('user_id', $user->id)
            ->with(['orderitems.product', 'coupon'])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    /**
     * Display single order details.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function orderDetails($id)
    {
        $user = Auth::user();
        
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['orderitems.product', 'coupon'])
            ->firstOrFail();

        return view('customer.order-details', compact('order'));
    }

    /**
     * Display customer wishlist.
     *
     * @return \Illuminate\View\View
     */
    public function wishlist()
    {
        $wishlistItems = $this->wishlistService->getAll(['product.category', 'product.type', 'product.manfacturer']);

        return view('customer.wishlist', compact('wishlistItems'));
    }

    /**
     * Display customer profile.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    /**
     * Display customer quote requests.
     */
    public function quoteRequests()
    {
        $quoteRequests = $this->quoteRequestService->getForUser((int) Auth::id(), 10);

        return view('customer.quote-requests', compact('quoteRequests'));
    }

    /**
     * Display single customer quote request.
     */
    public function quoteRequestDetails(int $id)
    {
        $quoteRequest = $this->quoteRequestService->getForUserById($id, (int) Auth::id());

        return view('customer.quote-request-details', compact('quoteRequest'));
    }

    /**
     * Update customer profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('customer.account.profile')
            ->with('success', __('frontend.profile_updated'));
    }
}
