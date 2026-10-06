<?php

use App\Models\Costumer;
use App\Models\Orderitem;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CostumerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderItemController;
use App\Http\Controllers\Admin\ManfacturerController;
use App\Http\Controllers\Admin\webconfigController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Serve storage files when symlink is missing (e.g. shared hosting)
Route::get('/storage/{path}', [\App\Http\Controllers\StorageLinkController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.serve');

Route::redirect('/admin', '/admin/login');



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/edit_profile',[ProfileController::class,'edit_profile']);
    Route::post('/update_profile',[ProfileController::class,'update_profile']);
    Route::post('/update_password',[ProfileController::class,'update_password']);
    Route::resource('/category' ,CategoryController::class );
    Route::resource('/type' ,TypeController::class );
    Route::resource('/manfacturer' ,ManfacturerController::class );
    Route::resource('/product' ,ProductController::class );

    // Product Details Management Routes
    Route::post('/product/{productId}/images', [\App\Http\Controllers\Admin\ProductImageController::class, 'store'])->name('product.images.store');
    Route::put('/product/images/{id}', [\App\Http\Controllers\Admin\ProductImageController::class, 'update'])->name('product.images.update');
    Route::delete('/product/images/{id}', [\App\Http\Controllers\Admin\ProductImageController::class, 'destroy'])->name('product.images.destroy');
    Route::post('/product/images/sort', [\App\Http\Controllers\Admin\ProductImageController::class, 'updateSortOrder'])->name('product.images.sort');

    Route::post('/product/{productId}/specifications', [\App\Http\Controllers\Admin\ProductSpecificationController::class, 'store'])->name('product.specifications.store');
    Route::put('/product/specifications/{id}', [\App\Http\Controllers\Admin\ProductSpecificationController::class, 'update'])->name('product.specifications.update');
    Route::delete('/product/specifications/{id}', [\App\Http\Controllers\Admin\ProductSpecificationController::class, 'destroy'])->name('product.specifications.destroy');

    Route::post('/product/{productId}/videos', [\App\Http\Controllers\Admin\ProductVideoController::class, 'store'])->name('product.videos.store');
    Route::put('/product/videos/{id}', [\App\Http\Controllers\Admin\ProductVideoController::class, 'update'])->name('product.videos.update');
    Route::delete('/product/videos/{id}', [\App\Http\Controllers\Admin\ProductVideoController::class, 'destroy'])->name('product.videos.destroy');

    Route::post('/product/{productId}/documents', [\App\Http\Controllers\Admin\ProductDocumentController::class, 'store'])->name('product.documents.store');
    Route::put('/product/documents/{id}', [\App\Http\Controllers\Admin\ProductDocumentController::class, 'update'])->name('product.documents.update');
    Route::delete('/product/documents/{id}', [\App\Http\Controllers\Admin\ProductDocumentController::class, 'destroy'])->name('product.documents.destroy');

    Route::patch('/order/{order}/status', [OrderController::class, 'updateStatus'])->name('order.status.update');
    Route::resource('/order' ,OrderController::class );
    Route::resource('/costumer' ,CostumerController::class );
    Route::resource('/ad' ,AdController::class );
    Route::resource('/slider' ,SliderController::class );
    Route::post('/webconfig', [webconfigController::class, 'saveOrUpdate']);
    Route::get('/webconfig', [webconfigController::class, 'index']);

    Route::resource('/orderitem' ,OrderItemController::class );
    Route::resource('/order' ,OrderController::class );

    Route::resource('/users',UserController::class);

    // Review Management Routes
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{id}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::delete('/reviews/{id}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('admin.reviews.reject');

    // Quote Request Management Routes
    Route::get('/quote-requests', [\App\Http\Controllers\Admin\QuoteRequestController::class, 'index'])->name('admin.quote-requests.index');
    Route::get('/quote-requests/{id}', [\App\Http\Controllers\Admin\QuoteRequestController::class, 'show'])->name('admin.quote-requests.show');
    Route::put('/quote-requests/{id}', [\App\Http\Controllers\Admin\QuoteRequestController::class, 'update'])->name('admin.quote-requests.update');

    Route::get('/dashboard', [DashboardController::class , 'index']);

    // Coupon Management Routes
    Route::resource('/coupon', \App\Http\Controllers\Admin\CouponController::class);

});
Route::get('/', [FrontendController::class , 'index']);
Route::get('/cart', [FrontendController::class , 'cart']);
Route::get('/products', [FrontendController::class , 'product'])->name('products');
Route::get('/search', [FrontendController::class , 'search'])->name('search');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/aboutus', [FrontendController::class , 'aboutus']);
Route::get('/contactus', [FrontendController::class , 'contactus']);
Route::get('/details/{id}', [FrontendController::class , 'show'])->name('details');
Route::get('/quickview/{id}', [FrontendController::class , 'quickView'])->name('quickview');
Route::post('/check',[FrontendController::class , 'store'])->middleware('throttle:checkout');
Route::get('/checkout',[FrontendController::class , 'checkout'])->name('checkout');
Route::get('/checkout/confirmation/{order}',[FrontendController::class , 'confirmation'])->name('checkout.confirmation');
Route::post('/apply-coupon',[FrontendController::class , 'applyCoupon'])->middleware('throttle:cart')->name('apply.coupon');
Route::post('/remove-coupon',[FrontendController::class , 'removeCoupon'])->middleware('throttle:cart')->name('remove.coupon');
Route::get('change_language/{lang}' , [LanguageController::class , 'change'])->name('change_language');
Route::post('cart', [CartController::class, 'addToCart'])->middleware('throttle:cart')->name('cart.store');
Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
Route::post('update-cart', [CartController::class, 'updateCart'])->middleware('throttle:cart')->name('cart.update');
Route::post('remove', [CartController::class, 'removeCart'])->middleware('throttle:cart')->name('cart.remove');
Route::post('clear', [CartController::class, 'clearAllCart'])->middleware('throttle:cart')->name('cart.clear');

// Customer Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
    Route::post('/register', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'register']);
    Route::get('/login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'logout'])->name('customer.logout');

    // Customer Account Routes
    Route::prefix('account')->name('customer.account.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CustomerAccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\CustomerAccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [\App\Http\Controllers\CustomerAccountController::class, 'orderDetails'])->name('order.details');
        Route::get('/quote-requests', [\App\Http\Controllers\CustomerAccountController::class, 'quoteRequests'])->name('quote_requests');
        // Register before /quote-requests/{id} so /quote-requests/{id}/pdf-data is never mis-resolved.
        Route::get('/quote-requests/{batch}/pdf-data', [\App\Http\Controllers\QuoteRequestPdfDataController::class, 'showForAccount'])
            ->name('quote_requests.pdf-data');
        Route::get('/quote-requests/{id}', [\App\Http\Controllers\CustomerAccountController::class, 'quoteRequestDetails'])->name('quote_requests.details');
        Route::get('/wishlist', [\App\Http\Controllers\CustomerAccountController::class, 'wishlist'])->name('wishlist');
        Route::get('/profile', [\App\Http\Controllers\CustomerAccountController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\CustomerAccountController::class, 'updateProfile'])->name('profile.update');
    });
});

// Wishlist Routes (available to both guests and authenticated users)
Route::post('/wishlist/add', [\App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');
Route::delete('/wishlist/remove/{productId}', [\App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');
Route::get('/wishlist/count', [\App\Http\Controllers\WishlistController::class, 'count'])->name('wishlist.count');
Route::get('/wishlist/check/{productId}', [\App\Http\Controllers\WishlistController::class, 'check'])->name('wishlist.check');

// Review Routes
Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::post('/reviews/{id}/helpful', [\App\Http\Controllers\ReviewController::class, 'markHelpful'])->name('reviews.helpful');
Route::get('/products/{productId}/reviews', [\App\Http\Controllers\ReviewController::class, 'getProductReviews'])->name('reviews.product');

Route::middleware('auth')->group(function () {
// Quote Request Routes
Route::get('/quote-requests/create', [\App\Http\Controllers\QuoteRequestController::class, 'create'])->name('quote-request.create');
Route::post('/quote-requests', [\App\Http\Controllers\QuoteRequestController::class, 'store'])->middleware('throttle:quote')->name('quote-request.store');
Route::get('/quote-requests/{batch}/pdf-data', [\App\Http\Controllers\QuoteRequestPdfDataController::class, 'show'])
    ->middleware('signed')
    ->name('quote-request.pdf-data');
});
// Newsletter Routes
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [\App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Product Comparison Routes
Route::get('/comparison', [\App\Http\Controllers\ProductComparisonController::class, 'index'])->name('comparison.index');
Route::post('/comparison/add', [\App\Http\Controllers\ProductComparisonController::class, 'add'])->name('comparison.add');
Route::delete('/comparison/remove/{productId}', [\App\Http\Controllers\ProductComparisonController::class, 'remove'])->name('comparison.remove');
Route::post('/comparison/clear', [\App\Http\Controllers\ProductComparisonController::class, 'clear'])->name('comparison.clear');
Route::get('/comparison/count', [\App\Http\Controllers\ProductComparisonController::class, 'count'])->name('comparison.count');
Route::get('/comparison/check/{productId}', [\App\Http\Controllers\ProductComparisonController::class, 'check'])->name('comparison.check');

// Restock Notification Routes
Route::post('/restock-notification/subscribe', [\App\Http\Controllers\RestockNotificationController::class, 'subscribe'])->name('restock-notification.subscribe');
Route::get('/restock-notification/check/{productId}', [\App\Http\Controllers\RestockNotificationController::class, 'check'])->name('restock-notification.check');

require __DIR__ . '/auth.php';
