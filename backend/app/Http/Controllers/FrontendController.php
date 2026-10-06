<?php

namespace App\Http\Controllers;

use App\Constants\OrderStatus;
use App\Http\Controllers\Concerns\PreparesViewData;
use App\Models\Ad;
use App\Models\Slider;
use App\Models\Category;
use App\DTOs\OrderDTO;
use App\Http\Requests\StoreOrderRequest;
use App\Services\LoggingService;
use App\Services\OrderService;
use App\Services\ProductService;
use Hnooz\LaravelCart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller
{
    use PreparesViewData;

    public function __construct(
        protected ProductService $productService,
        protected OrderService $orderService,
        protected LoggingService $loggingService
    ) {}

    /**
     * Display the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Use optimized methods to avoid multiple getAll() calls
        $newArrivalProducts = $this->productService->getNewArrivals(7);
        $bestProducts = $this->productService->getBestProducts(7);

        // Cache static data that doesn't change often
        $categories = cache()->remember(
            \App\Services\Helpers\CacheHelper::CATEGORIES,
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => Category::all()
        );
        $sliders = cache()->remember(
            \App\Services\Helpers\CacheHelper::SLIDERS,
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => Slider::all()
        );
        $ads = cache()->remember(
            \App\Services\Helpers\CacheHelper::ADS,
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => Ad::all()
        );

        // Only load all products if needed for the view
        $products = $this->productService->getAll();
        $cartItems = $this->getCartItems();

        // SEO data
        $locale = Session::get('locale', 'en');
        $pageTitle = $locale === 'ar' ? 'رين تكنولجي - الرئيسية' : 'Rain Technology - Home';
        $pageDescription = $locale === 'ar'
            ? 'شركة متخصصة في بيع الأجهزة الكهربائية والإلكترونية، أنظمة التحكم في المنزل الذكي، الأنظمة الأمنية، كاميرات المراقبة في الكويت.'
            : 'We are a company for the retail sale of electrical and electronic devices, smart home control systems, security systems, surveillance cameras in Kuwait.';
        $canonicalUrl = url('/');

        return view('frontend.index')
            ->with('new_arrival_products', $newArrivalProducts)
            ->with('best_products', $bestProducts)
            ->with('sildering', $sliders)
            ->with('cartItems', $cartItems)
            ->with('ading', $ads)
            ->with('categories', $categories)
            ->with('products', $products)
            ->with('pageTitle', $pageTitle)
            ->with('pageDescription', $pageDescription)
            ->with('canonicalUrl', $canonicalUrl);
    }
    /**
     * Display the checkout page.
     * Available to both authenticated users and guests.
     *
     * @return \Illuminate\View\View
     */
    public function checkout()
    {
        $lastOrderAddress = null;

        // Get last order address if user is logged in
        if (Auth::check()) {
            $lastOrder = Auth::user()->orders()->latest()->first();
            $lastOrderAddress = $lastOrder ? $lastOrder->address : null;
        }

        return $this->viewWithCart('frontend.checkout', [
            'lastOrderAddress' => $lastOrderAddress,
        ]);
    }

    /**
     * Validate and apply coupon code.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function applyCoupon(\Illuminate\Http\Request $request)
    {
        // Validate - Laravel automatically handles JSON requests
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:8'],
        ]);

        // Get validated coupon code
        $couponCode = $validated['coupon_code'];

        $coupon = \App\Models\Coupon::find($couponCode);

        if (!$coupon) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code.',
                ], 422);
            }
            return redirect()->route('checkout')
                ->with('coupon_error', 'Invalid coupon code.')
                ->withInput();
        }

        // Calculate discount
        $cartItems = Cart::all();
        $baseTotal = $this->orderService->calculateTotal($cartItems);
        $discount = $coupon->amount;
        $finalTotal = max(0, $baseTotal - $discount);

        // Store coupon info in session
        session([
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->id,
            'coupon_discount' => $discount,
            'final_total' => $finalTotal,
            'coupon_success' => 'Coupon applied successfully!',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'data' => [
                    'coupon_id' => $coupon->id,
                    'coupon_code' => $coupon->id,
                    'coupon_discount' => $discount,
                    'subtotal' => $baseTotal,
                    'final_total' => $finalTotal,
                ],
            ]);
        }

        return redirect()->route('checkout')
            ->with('success', 'Coupon applied successfully!');
    }

    /**
     * Remove applied coupon.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function removeCoupon(\Illuminate\Http\Request $request)
    {
        $cartItems = Cart::all();
        $baseTotal = $this->orderService->calculateTotal($cartItems);
        
        session()->forget(['coupon_id', 'coupon_code', 'coupon_discount', 'final_total', 'coupon_success', 'coupon_error']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon removed.',
                'data' => [
                    'subtotal' => $baseTotal,
                    'final_total' => $baseTotal,
                ],
            ]);
        }

        return redirect()->route('checkout')
            ->with('success', 'Coupon removed.');
    }

    /**
     * Display order confirmation page.
     * Available to both authenticated users and guests who placed the order.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function confirmation(\App\Models\Order $order)
    {
        // Load order with relationships
        $order->load(['orderitems.product', 'coupon']);

        // Check if order exists
        if (!$order) {
            return redirect()->route('cart.list')
                ->with('error', 'Order not found.');
        }

        return view('frontend.confirmation', compact('order'));
    }

    /**
     * Display the shopping cart page.
     *
     * @return \Illuminate\View\View
     */
    public function cart()
    {
        return $this->viewWithCart('frontend.cart');
    }

    /**
     * Process order creation from checkout.
     * Supports both authenticated users and guest users.
     *
     * @param StoreOrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {

        try {
            $cartItems = Cart::all();

            if (empty($cartItems)) {
                return redirect()->route('cart.list')
                    ->with('error', 'Your cart is empty. Please add items before checkout.');
            }

            // Calculate base total
            $baseTotal = $this->orderService->calculateTotal($cartItems);

            // Apply coupon if provided
            $couponId = null;
            $finalTotal = $baseTotal;

            if ($request->filled('coupon_id')) {
                $coupon = \App\Models\Coupon::find($request->coupon_id);
                if ($coupon) {
                    $couponId = $coupon->id; // Coupon ID is a string
                    // Apply discount (assuming amount is a fixed discount amount)
                    $finalTotal = max(0, $baseTotal - $coupon->amount);
                }
            }

            // Create OrderDTO
            // Note: Guest orders are supported - user_id will be null for guest users
            $orderDTO = OrderDTO::fromArray([
                'user_id' => Auth::id(), // null for guest users, user ID for authenticated users
                'costumer_name' => $request->costumer_name,
                'costumer_number' => $request->costumer_number,
                'address' => $request->address,
                'total' => $finalTotal,
                'order_status' => OrderStatus::PENDING,
                'note' => $request->note,
                'coupon_id' => $couponId, // Can be null if no coupon
            ]);

            // Create order using service
            $order = $this->orderService->createOrder($orderDTO, $cartItems);

            Cart::clear();

            // Clear coupon session data after successful order
            session()->forget(['coupon_id', 'coupon_code', 'coupon_discount', 'final_total', 'coupon_success', 'coupon_error']);

            // Redirect to confirmation page with order ID
            return redirect()->route('checkout.confirmation', ['order' => $order->id])
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            
            // Error logging is handled in OrderService
            // Additional logging here if needed for controller-specific context
            $this->loggingService->logOrder('error', 'Order creation failed in controller', [
                'error' => $e->getMessage(),
                'customer_name' => $request->costumer_name,
                'customer_number' => $request->costumer_number,
            ]);

            return redirect()->route('checkout')
                ->withInput()
                ->with('error', $e->getMessage() ?: 'An error occurred while processing your order. Please try again.');
        }
    }
    /**
     * Display product details page.
     *
     * @param int $id Product ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            // Load product with all relationships
            $product = $this->productService->getById($id, [
                'category',
                'type',
                'manfacturer',
                'images',
                'specifications',
                'videos',
                'documents',
                'approvedReviews.user'
            ]);

            $productDiscount = $product->discount_percentage;
            $productPriceAfterDiscount = $product->price_after_discount;
            $category = $product->category;
            $cartItems = Cart::all();

            // Get related products (same category, excluding current product)
            $relatedProducts = $this->productService->getRelatedProducts($product->id, $product->category_id, 4);

            // Get compatible products
            $compatibleProducts = app(\App\Services\ProductComparisonService::class)->getCompatibleProducts($product->id);

            // Get review statistics
            $reviewStats = app(\App\Services\ReviewService::class)->getProductReviewStats($product->id);
            $reviews = $product->approvedReviews()->with('user')->orderBy('created_at', 'DESC')->paginate(5);

            // SEO data
            $locale = Session::get('locale', 'en');
            $productName = $locale === 'ar' ? $product->name_ar : $product->name_en;
            $productDescription = $locale === 'ar' ? $product->description_ar : $product->description_en;
            $pageTitle = $productName . ' - Rain Technology';
            $pageDescription = mb_substr(strip_tags($productDescription), 0, 160) ?: ($locale === 'ar' ? 'منتج عالي الجودة من رين تكنولوجي' : 'High quality product from Rain Technology');
            $pageImage = $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset($product->photo);
            $canonicalUrl = url("/details/{$product->id}");

            return view('frontend.details')
                ->with('product', $product)
                ->with('product_discount', $productDiscount)
                ->with('cartItems', $cartItems)
                ->with('product_price_after_discount', $productPriceAfterDiscount)
                ->with('category', $category)
                ->with('relatedProducts', $relatedProducts)
                ->with('compatibleProducts', $compatibleProducts)
                ->with('reviews', $reviews)
                ->with('reviewStats', $reviewStats)
                ->with('pageTitle', $pageTitle)
                ->with('pageDescription', $pageDescription)
                ->with('pageImage', $pageImage)
                ->with('canonicalUrl', $canonicalUrl);
        } catch (\App\Exceptions\ProductNotFoundException $e) {
            return redirect()->route('products')
                ->with('error', 'Product not found.');
        }
    }
    /**
     * Display all products page.
     *
     * @return \Illuminate\View\View
     */
    public function product()
    {
        $products = $this->productService->getAll();
        $cartItems = Cart::all();
        $productsCount = $products->count();
        $ads = cache()->remember(
            \App\Services\Helpers\CacheHelper::ADS,
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => Ad::all()
        );

        // SEO data
        $locale = Session::get('locale', 'en');
        $pageTitle = $locale === 'ar' ? 'جميع المنتجات - رين تكنولجي' : 'All Products - Rain Technology';
        $pageDescription = $locale === 'ar'
            ? 'تصفح جميع منتجاتنا من الأجهزة الكهربائية والإلكترونية، أنظمة التحكم في المنزل الذكي، والأنظمة الأمنية.'
            : 'Browse all our products including electrical and electronic devices, smart home control systems, and security systems.';
        $canonicalUrl = url('/products');

        return view('frontend.products')
            ->with('products', $products)
            ->with('products_count', $productsCount)
            ->with('cartItems', $cartItems)
            ->with('ading', $ads)
            ->with('pageTitle', $pageTitle)
            ->with('pageDescription', $pageDescription)
            ->with('canonicalUrl', $canonicalUrl);
    }

    /**
     * Get product data for quick view modal.
     *
     * @param int $id Product ID
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function quickView($id)
    {
        try {
            $product = $this->productService->getById($id, [
                'category',
                'type',
                'manfacturer',
                'images'
            ]);

            $productDiscount = $product->discount_percentage;
            $productPriceAfterDiscount = $product->price_after_discount;
            $category = $product->category;

            // Return JSON for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'product' => [
                        'id' => $product->id,
                        'name_en' => $product->name_en,
                        'name_ar' => $product->name_ar,
                        'description_en' => $product->description_en,
                        'description_ar' => $product->description_ar,
                        'price' => $product->price,
                        'discount' => $product->discount,
                        'price_after_discount' => $productPriceAfterDiscount,
                        'discount_percentage' => $productDiscount,
                        'photo' => asset($product->photo),
                        'images' => $product->images->map(function($img) {
                            return asset('storage/' . $img->image_path);
                        })->toArray(),
                        'is_available' => $product->is_available,
                        'quantity' => $product->quantity,
                        'category' => [
                            'name_en' => $category->name_en ?? '',
                            'name_ar' => $category->name_ar ?? '',
                        ],
                    ],
                    'locale' => Session::get('locale', 'en'),
                ]);
            }

            // Return view for non-AJAX requests
            return view('frontend.quickview', compact('product', 'productDiscount', 'productPriceAfterDiscount', 'category'));
        } catch (\App\Exceptions\ProductNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('frontend.product_not_found')
                ], 404);
            }
            return redirect()->route('products')
                ->with('error', __('frontend.product_not_found_error'));
        }
    }

    /**
     * Search products.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function search(\Illuminate\Http\Request $request)
    {
        $query = $request->get('q', '');

        // Collect all filter parameters
        $filters = [
            'category_id' => $request->get('category'),
            'manufacturer_id' => $request->get('manufacturer'),
            'type_id' => $request->get('type'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'availability' => $request->get('availability'),
            'sort' => $request->get('sort', 'newest'),
        ];

        $products = $this->productService->search($query, $filters);
        $productsCount = $products->count();

        // Get filter options
        $categories = cache()->remember(
            \App\Services\Helpers\CacheHelper::CATEGORIES,
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => Category::all()
        );

        $manufacturers = cache()->remember(
            'manufacturers_all',
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => \App\Models\Manfacturer::all()
        );

        $types = cache()->remember(
            'types_all',
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => \App\Models\Type::all()
        );

        // Get price range for filter
        $priceRange = $this->productService->getAll()->pluck('price');
        $minPrice = $priceRange->min() ?? 0;
        $maxPrice = $priceRange->max() ?? 1000;

        // SEO data
        $locale = Session::get('locale', 'en');
        $pageTitle = $query
            ? ($locale === 'ar' ? "نتائج البحث عن: {$query} - رين تكنولجي" : "Search Results for: {$query} - Rain Technology")
            : ($locale === 'ar' ? 'نتائج البحث - رين تكنولجي' : 'Search Results - Rain Technology');
        $pageDescription = $locale === 'ar'
            ? "نتائج البحث عن المنتجات في رين تكنولجي"
            : "Search results for products at Rain Technology";
        $canonicalUrl = url()->current();

        return view('frontend.search')
            ->with('products', $products)
            ->with('products_count', $productsCount)
            ->with('query', $query)
            ->with('categories', $categories)
            ->with('manufacturers', $manufacturers)
            ->with('types', $types)
            ->with('min_price', $minPrice)
            ->with('max_price', $maxPrice)
            ->with('filters', $filters)
            ->with('cartItems', $this->getCartItems())
            ->with('pageTitle', $pageTitle)
            ->with('pageDescription', $pageDescription)
            ->with('canonicalUrl', $canonicalUrl);
    }

    /**
     * Display about us page.
     *
     * @return \Illuminate\View\View
     */
    public function aboutus()
    {
        return $this->viewWithCart('frontend.aboutus');
    }

    /**
     * Display contact us page.
     *
     * @return \Illuminate\View\View
     */
    public function contactus()
    {
        return $this->viewWithCart('frontend.contactus');
    }

}
