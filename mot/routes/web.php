<?php

use App\Models\Currency;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\CustomerAuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomerNewPasswordController;
use App\Http\Controllers\Auth\CustomerPasswordResetLinkController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\Auth\CustomerEmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Customer\BrandController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\CompareProductsController;
use App\Http\Controllers\Customer\ContactUsController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CategoryController;
use App\Http\Controllers\Customer\MyAccountController;
use App\Http\Controllers\Customer\PagesController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ProductReviewsController;
use App\Http\Controllers\Customer\StoreReviewsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\RateRequestController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RequestProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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

Route::get('get_location', function(){
//    $ip = '103.239.147.187'; //For static IP address get
    $ip = request()->ip(); //Dynamic IP address get
//    $ip = request()->server('SERVER_ADDR');
    $data = \Location::get($ip);
    return $data;
});
Route::get('dismiss_top_notification', function (){
    Session::put('top_notification', 1);
return response()->json(true);
});

Route::get('locale/{locale}', function ($locale){
    Session::put('locale_web', $locale);
//    App::setLocale($locale);
    return redirect()->back();
});
Route::post('newsletter', [HomeController::class, 'newsletter'])->name('newsletter');
Route::get('generate-store-sku', [HomeController::class, 'generateStoreSku']);


Route::get('currency/{currency}', function (Currency $currency){
    setCurrency($currency);
    return redirect()->back();
})->name('currency');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/exportCsv', [HomeController::class, 'exportCsv'])->name('exportCsv');
Route::get('store/{slug}', [HomeController::class, 'show'])->name('store.show');
Route::get('products', [ProductController::class, 'index'])->name('products');
Route::get('search', [ProductController::class, 'search'])->name('search');
Route::get('get-product', [ProductController::class, 'getProducts'])->name('get.product');
Route::get('category/{slug}', [ProductController::class, 'index'])->name('category');
Route::get('daily-deals', [ProductController::class, 'dailyDeals'])->name('daily-deals');
Route::get('flash-deals', [ProductController::class, 'flashDeals'])->name('flash-deals');
Route::get('categories', [CategoryController::class, 'index'])->name('categories');
Route::get('brands', [BrandController::class, 'index'])->name('brands');
Route::get('stores', [StoreController::class, 'index'])->name('stores');
Route::get('login-register', [CustomerRegisterController::class, 'create'])->name('login-register');
Route::post('customer-register', [CustomerRegisterController::class, 'store'])->name('customer-register');
Route::post('customer-login', [CustomerAuthenticatedSessionController::class, 'store'])->name('customer-login');
Route::get('contact-us', [ContactUsController::class, 'index'])->name('contact-us');
Route::post('add-contact-us', [ContactUsController::class, 'store'])->name('add-contact-us');
Route::get('seller-register', [SellerController::class, 'showRegisterForm'])->name('seller-register');
Route::post('seller-register', [SellerController::class, 'saveSeller']);
Route::get('seller-registered-success', [SellerController::class, 'registeredSuccess'])->name('seller-registered-success');
Route::get('get-states', [CustomerController::class, 'getStates'])->name('get-states');
Route::get('get-cities', [CustomerController::class, 'getCities'])->name('get-cities');
Route::post('check-guest-account', [CustomerController::class, 'checkGuestAccount'])->name('check-guest-account');


Route::middleware(['auth:customer'])->group(function(){
    Route::get('my-account', [MyAccountController::class, 'show'])->name('my-account');
    Route::post('update-profile', [CustomerController::class, 'updateCustomerInfo'])->name('update-profile');
    Route::get('change-password', [CustomerController::class, 'changePassword'])->name('change-password');
    Route::get('logout', [CustomerAuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('address', [AddressController::class, 'index'])->name('list-address');
    Route::get('address/{id}', [AddressController::class, 'getById']);
    Route::post('add-address', [CustomerController::class, 'store'])->name('add-address');
    Route::post('edit-address/{id}', [CustomerController::class, 'update'])->name('edit-address.id');
    Route::post('delete-address', [CustomerController::class, 'destroy'])->name('delete-address');
    // @tahir please fix the following route .. artisan route:list complains at this line.
//    Route::resource('customer-address','App\Http\Controllers\Web\CustomerController');
    Route::post('add-identity-number', [CustomerController::class, 'addIdentityNumber'])->name('add-identity-number');

    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('add-to-wishlist/{id}', [ProductController::class, 'addToWishlist'])->name('add.wishlist');
    Route::get('remove-from-wishlist/{productId}', [ProductController::class, 'removeFromWishlist'])->name('remove-from-wishlist');

    Route::get('history', [OrderController::class, 'index'])->name('order-history');
    Route::get('store-review/{id}', [OrderController::class, 'storeReview'])->name('store-order-review');
    Route::get('cancellation', [OrderController::class, 'cancellation'])->name('cancellation');
    Route::get('order/{storeOrder}', [OrderController::class, 'show'])->name('order-detail');
    Route::get('track-package/{order}', [OrderController::class, 'trackPackage'])->name('track-package');

    Route::get('order-return-form/{id}', [OrderController::class, 'orderReturnProcess'])->name('order-return-form');
    Route::post('order-return-request', [OrderController::class, 'orderReturnRequest'])->name('order-return-request');
    Route::post('update-order-return-request', [OrderController::class, 'updateReturnRequest'])->name('update-order-return-request');
    Route::post('gallery-upload', [OrderController::class, 'galleryUpload'])->name('gallery.upload');
    Route::post('gallery-delete', [OrderController::class, 'galleryDelete'])->name('gallery.delete');

    Route::get('order-cancel-request/{order}', [OrderController::class, 'orderCancelRequest'])->name('order-cancel-request');
    Route::post('cancel-order', [OrderController::class, 'createCancelOrderRequest'])->name('cancel-order');
    Route::post('create-archive-order', [OrderController::class, 'createArchiveOrder'])->name('create-archive-order');

    Route::get('customer-product-reviews', [ProductReviewsController::class, 'index'])->name('customer-product-reviews');
    Route::post('gallery-upload', [ProductReviewsController::class, 'galleryUpload'])->name('gallery.upload');
    Route::post('gallery-delete', [ProductReviewsController::class, 'galleryDelete'])->name('gallery.delete');
    Route::get('customer-product-reviews/{id}', [ProductReviewsController::class, 'show'])->name('show-customer-product-reviews');
    Route::post('customer-product-reviews', [ProductReviewsController::class, 'store'])->name('create-customer-product-reviews');

    Route::get('customer-store-reviews', [StoreReviewsController::class, 'index'])->name('customer-store-reviews');
    Route::get('customer-store-reviews/{id}', [StoreReviewsController::class, 'show'])->name('show-customer-store-reviews');
    Route::post('customer-store-reviews', [StoreReviewsController::class, 'store'])->name('create-customer-store-reviews');
    Route::post('store-review', [StoreReviewsController::class, 'storeReview'])->name('store-review');


    Route::post('update-customer-info', [CustomerController::class, 'updateCustomerInfo'])->name('update-customer-info');
    Route::post('change-password', [CustomerController::class, 'customerChangePassword']);

    Route::get('image-upload-preview', [CustomerController::class, 'avatar']);
    Route::post('upload-avatar', [CustomerController::class, 'storeAvatar']);
});

Route::get('request-product', [RequestProductController::class, 'index'])->name('request-product');
Route::post('request-product', [RequestProductController::class, 'store']);

Route::get('trending/{trendingProduct}', [ProductController::class, 'trending'])->name('trending');
Route::get('tabbed/{tabbedProduct}', [ProductController::class, 'tabbed'])->name('tabbed');
Route::get('new-arrival/', [ProductController::class, 'newArrival'])->name('new-arrival');
Route::get('price-under-one', [ProductController::class, 'underOne'])->name('price-under-one');

//Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//   $request->fulfill();
//   return redirect('/home');
//})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('customer-forgot-password', [CustomerPasswordResetLinkController::class, 'create'])->name('customer-forgot-password');
Route::post('send-forgot-password-link', [CustomerPasswordResetLinkController::class, 'store'])->name('send-forgot-password-link');

Route::get('/reset-password/{token}', [CustomerNewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [CustomerNewPasswordController::class, 'store'])->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->name('verification.verify');

Route::post('/email/verification-notification', [CustomerEmailVerificationNotificationController::class, 'store'])->name('verification.send');
Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');

Route::post('/confirm-password-store', [ConfirmablePasswordController::class, 'store'])->middleware('auth')->name('password.store');
Route::any('/logout', [CustomerAuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('customer-logout');

Route::get('product/{slug}/{product}', [ProductController::class, 'detail'])->name('product');

/* compare product routes start */
Route::get('compare-product', [CompareProductsController::class, 'index'])->name('compare-product');
Route::get('add-compare-product/{id}', [CompareProductsController::class, 'addCompareProduct'])->name('add-compare-product');
Route::get('remove-compare-product/{id}', [CompareProductsController::class, 'removeCompareProduct'])->name('remove-compare-product');
/* compare product routes end */

Route::get('brand/{slug}', [ProductController::class, 'brand'])->name('brand');
Route::get('shop/{slug}', [StoreController::class, 'show'])->name('shop');
Route::post('store-question', [StoreController::class, 'storeQuestion'])->name('store-question');

/* cart routes */
Route::get('cart', [CartController::class, 'index'])->name('cart');
Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::post('remove-cart-item', [CartController::class, 'removeItem'])->name('remove-cart-item');

Route::post('dhl-rate-request',[RateRequestController::class,'rateRequest'] )->name('dhl.rate.request');

Route::get('remove-cart-message', [CartController::class, 'removeCartMessage'])->name('apply-coupon');
Route::get('empty-cart', [CartController::class, 'emptyCart'])->name('empty-cart');
Route::post('apply-coupon', [CartController::class, 'applyCoupon']);
// payment routes starts
Route::post('place_order', [PaymentController::class, 'placeOrder'])->name('place_order');
Route::post('add_guest_address', [PaymentController::class, 'addGuestCustomer'])->name('add_guest_address');
Route::post('create_order', [PaymentController::class, 'createOrder'])->name('create_order');


Route::post('iyzico/payment/{transactionAttempt}', [PaymentController::class, 'iyzicoCallback'])->name('iyzico-callback');
Route::get('thank-you/{order}', [PaymentController::class, 'thankYou'])->name('thank-you');
/* myFatoorah routes */
Route::get('myfatoorah/verify/{transactionAttemptId}', [PaymentController::class, 'myFatoorahCallback'])->name('myfatoorah-callback');
// payment routes ends
require __DIR__ . '/auth.php';

// resize image
Route::get('resize/{width}/{height}/{image}', function ($width, $height, $image) {
    $originalImage = storage_path() . config('media.path.original') . $image;
    try {
        return Image::make($originalImage)->resize($width, $height)->response();
    } catch (\Throwable $throwable) {
        \Illuminate\Support\Facades\Log::critical($throwable->getMessage() . ' :: ' . $originalImage);
    }
})->name('resize');

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sitemap.xml/products', [SitemapController::class, 'getProducts']);
Route::get('/sitemap.xml/categories', [SitemapController::class, 'getCategories']);
Route::get('/sitemap.xml/stores', [SitemapController::class, 'getStores']);
Route::get('/sitemap.xml/brands', [SitemapController::class, 'getBrands']);
Route::get('/sitemap.xml/pages', [SitemapController::class, 'getPages']);
Route::get('/sitemap.xml/tags', [SitemapController::class, 'getTags']);

// single pages
Route::get('/{slug}', [PagesController::class, 'show'])->name('page');
Route::get('/500', fn() => abort(500));