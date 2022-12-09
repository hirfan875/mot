<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\StoreController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ReportAbuseController;
use App\Http\Controllers\API\BlockStoreController;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */



Route::post('login', [AuthController::class, 'signin']);
Route::post('register', [AuthController::class, 'signup']);
Route::post('social-register', [AuthController::class, 'socialSignup']);
Route::post('forgot-password', [AuthController::class, 'store']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::get('home', [HomeController::class, 'index']);
Route::get('categories', [ProductController::class, 'getAllCategories']);
Route::post('products', [ProductController::class, 'index']);
Route::post('compare-products', [ProductController::class, 'compareProducts']);
Route::get('flash-deals', [ProductController::class, 'flashDeals']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('coupons/product/{id}', [ProductController::class, 'getCouponsByProId']);
Route::get('stores', [StoreController::class, 'index']);
Route::get('stores/{slug}', [StoreController::class, 'show']);
Route::get('currencies', [HomeController::class, 'getCurrencies']);
Route::get('languages', [HomeController::class, 'getLanguages']);
Route::post('submit-contact-us', [HomeController::class, 'storeContactUs']);
Route::post('request-product', [HomeController::class, 'requestProduct']);
Route::get('all-products', [ProductController::class, 'getAllProducts']);
Route::get('countries', [AddressController::class, 'getCountries']);
Route::get('get-states', [AddressController::class, 'getStates']);
Route::get('get-cities', [AddressController::class, 'getCities']);
Route::post('seller-register', [HomeController::class, 'saveSeller']);
Route::post('general-settings', [HomeController::class, 'generalSettings']);
Route::get('splash-banner', [HomeController::class, 'splashBanner']);
Route::get('coupons', [HomeController::class, 'getCoupons']);
Route::post('ask-to-seller', [StoreController::class, 'storeQuestion']);
Route::get('inquiries', [HomeController::class, 'getInquiries']);
Route::get('help-center', [HomeController::class, 'getHelpCenter']);
Route::post('clear-cart', [CartController::class, 'clearCart']);

Route::post('report-abuse', [ReportAbuseController::class, 'store']);
Route::post('block-store', [BlockStoreController::class, 'store']);


/*notifications*/
Route::post('notifications', [NotificationController::class, 'index']);
Route::delete('delete-notification', [NotificationController::class, 'delete']);
Route::get('test-notification', [NotificationController::class, 'testNotification']);
Route::post('change-language', [NotificationController::class, 'changeLanguage']);
Route::post('change-notification-toggle', [NotificationController::class, 'changeToggle']);

/*create order*/
Route::post('create-order', [PaymentController::class, 'createOrder']);
Route::post('place-order', [PaymentController::class, 'placeOrder']);
Route::post('verify-payment', [PaymentController::class, 'verify']);
Route::get('get-order-detail', [OrderController::class, 'getOrderDetail']);

/* myFatoorah routes */
Route::get('myfatoorah/verify/{transactionAttemptId}', [PaymentController::class, 'myFatoorahCallback']);
// payment routes ends

/* cart routes */
Route::get('cart', [CartController::class, 'index']);
Route::get('mini-cart', [CartController::class, 'topCartItems']);
Route::post('add-to-cart', [CartController::class, 'addToCart']);
Route::post('remove-cart-item', [CartController::class, 'removeItem']);
Route::post('apply-coupon', [CartController::class, 'applyCoupon']);
Route::post('newsletter', [HomeController::class, 'newsletter']);
Route::post('check-guest-account', [CustomerController::class, 'checkGuestAccount']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('address', AddressController::class);
//    Route::resource('customer', CustomerController::class);
    Route::get('profile', [CustomerController::class, 'index'] );
    Route::post('change-password', [AuthController::class, 'changePassword'] );
    Route::post('update-profile', [CustomerController::class, 'update']);
    Route::get('wishlist', [WishlistController::class, 'index'] );
    Route::get('add-to-wishlist/{id}', [WishlistController::class, 'addWishlist'] );
    Route::get('remove-from-wishlist/{productId}', [WishlistController::class, 'destroyWishlist'] );
    Route::get('wishlist-count', [WishlistController::class, 'wishlistCount'] );
    Route::post('store-review', [StoreController::class, 'storeReview']);
    Route::post('product-reviews', [ProductController::class, 'productReviews']);
    Route::get('recent-viewed-products', [ProductController::class, 'recentViewed']);

    Route::post('cancel-order', [OrderController::class, 'createCancelOrderRequest']);
    Route::post('order-return-request', [OrderController::class, 'orderReturnRequest']);
    Route::post('update-order-return-request', [OrderController::class, 'updateReturnRequest']);

    /*order routes*/
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders-list', [OrderController::class, 'ordersList']);
//    Route::post('cancel-order', [OrderController::class, 'createCancelOrder']);

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('get_location', function(){
//    $ip = '103.239.147.187'; //For static IP address get
    $ip = request()->ip(); //Dynamic IP address get
    $data = \Location::get($ip);
    return $data;
});
// single pages
Route::get('/{slug}', [HomeController::class, 'page']);