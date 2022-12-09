<?php

use Illuminate\Support\Facades\Route;

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

require __DIR__ . '/auth-seller.php';

Route::namespace('App\Http\Controllers\Seller')->group(function () {

    Route::get('locale/{locale}', function ($locale){
        Session::put('locale_seller', $locale);
        return redirect()->back();
    });

    Route::get('/', 'DashboardController@index')->name('dashboard');

    // profile routes
    Route::get('/profile', 'ProfileController@showForm')->name('profile');
    Route::post('/profile', 'ProfileController@formProcess');

    // store return address routes
    Route::get('/return-address', 'ReturnAddressController@showForm')->name('return.address');
    Route::post('/return-address', 'ReturnAddressController@formProcess');

    // store profile routes
    Route::get('/store-profile', 'StoreProfileController@showForm')->name('store.profile');
    Route::post('/store-profile', 'StoreProfileController@formProcess');

    // store detail routes
    Route::get('/store-detail', 'StoreProfileController@showEditStoreForm')->name('store.store-detail');
    Route::post('/store-detail-update/{store}', 'StoreProfileController@updateStoreDetails')->name('store.store-detail-update');

    // staff routes
    Route::get('/staff', 'StaffController@index')->name('staff');
    Route::get('/staff/add', 'StaffController@create')->name('staff.add');
    Route::post('/staff/add', 'StaffController@store');
    Route::get('/staff/edit/{staff}', 'StaffController@edit')->name('staff.edit');
    Route::post('/staff/edit/{staff}', 'StaffController@update');
    Route::get('/staff/delete/{staff}', 'StaffController@delete')->name('staff.delete');
    Route::post('/staff/update/status', 'StaffController@updateStatus')->name('staff.update.status');

    // products routes
    Route::get('/products', 'ProductsController@index')->name('products');
    Route::get('/products/add', 'ProductsController@create')->name('products.add');
    Route::post('/products/add', 'ProductsController@store');
    Route::get('/products/edit/{product}', 'ProductsController@edit')->name('products.edit');
    Route::post('/products/edit/{product}', 'ProductsController@update');
    Route::get('/products/delete/{product}', 'ProductsController@delete')->name('products.delete');
    Route::post('/products/update/status', 'ProductsController@updateStatus')->name('products.update.status');
    Route::post('/products/gallery/upload', 'ProductsController@galleryUpload')->name('products.gallery.upload');
    Route::post('/products/gallery/delete', 'ProductsController@galleryDelete')->name('products.gallery.delete');
    Route::post('/products/gallery/update-order', 'ProductsController@galleryUpdateOrder')->name('products.gallery.update.order');
    Route::post('/products/deleted/all', 'ProductsController@deleteAll')->name('products.deleted.all');
    // import products routes
    Route::get('/products/download-sample-excel', 'ProductsController@downloadSampleExcel')->name('products.download-sample-excel');
    Route::post('/products/import', 'ProductsController@import')->name('products.import');
    Route::post('/import-images-zip', 'ProductsController@importImagesZip')->name('products.import-images-zip');
    Route::get('/trendyol-products', 'ProductsController@getTrendyolProducts')->name('trendyol.products');
    
    // daily deals routes
    Route::get('/daily-deals', 'DailyDealsController@index')->name('daily.deals');
    Route::get('/daily-deals/add', 'DailyDealsController@create')->name('daily.deals.add');
    Route::post('/daily-deals/add', 'DailyDealsController@store');
    Route::get('/daily-deals/edit/{deal}', 'DailyDealsController@edit')->name('daily.deals.edit');
    Route::post('/daily-deals/edit/{deal}', 'DailyDealsController@update');
    Route::get('/daily-deals/delete/{deal}', 'DailyDealsController@delete')->name('daily.deals.delete');
    Route::post('/daily-deals/update/status', 'DailyDealsController@updateStatus')->name('daily.deals.update.status');

    // flash deals routes
    Route::get('/flash-deals', 'FlashDealsController@index')->name('flash.deals');
    Route::get('/flash-deals/add', 'FlashDealsController@create')->name('flash.deals.add');
    Route::post('/flash-deals/add', 'FlashDealsController@store');
    Route::get('/flash-deals/edit/{deal}', 'FlashDealsController@edit')->name('flash.deals.edit');
    Route::post('/flash-deals/edit/{deal}', 'FlashDealsController@update');
    Route::get('/flash-deals/delete/{deal}', 'FlashDealsController@delete')->name('flash.deals.delete');
    Route::post('/flash-deals/update/status', 'FlashDealsController@updateStatus')->name('flash.deals.update.status');

    // promotions routes
//    Route::prefix('promotions')->group(function () {

        // coupons routes
        Route::get('/promotions/coupons', 'CouponsController@index')->name('coupons');
        Route::get('/promotions/coupons/add', 'CouponsController@create')->name('coupons.add');
        Route::post('/promotions/coupons/add', 'CouponsController@store');
        Route::get('/promotions/coupons/edit/{coupon}', 'CouponsController@edit')->name('coupons.edit');
        Route::post('/promotions/coupons/edit/{coupon}', 'CouponsController@update');
        Route::get('/promotions/coupons/delete/{coupon}', 'CouponsController@delete')->name('coupons.delete');

        // free delivery products routes
        Route::get('/promotions/free-delivery', 'FreeDeliveryController@index')->name('free.delivery');
        Route::get('/promotions/free-delivery/add', 'FreeDeliveryController@create')->name('free.delivery.add');
        Route::post('/promotions/free-delivery/add', 'FreeDeliveryController@store');

        // bundled products routes
        Route::get('/promotions/bundled-products', 'BundledProductsController@index')->name('bundled.products');
        Route::get('/promotions/pending-products', 'PendingProductsController@index')->name('pending.products');
//    });

     // reports routes
//    Route::prefix('report')->group(function () {

        // purchase routes
        Route::get('/report/sales', 'ReportsController@sales')->name('report.sales');
        Route::get('/report/sales/export/{type}', 'ReportsController@export')->name('report.sales.export');
        Route::get('/report/group-sale', 'ReportsController@groupSales')->name('report.group.sale');
        Route::get('/report/group-sale/export/{type}', 'ReportsController@groupExport')->name('report.group.sale.export');
        Route::get('/report/group-sales-stores', 'ReportsController@groupSaleStores')->name('report.group.sales.stores');
        Route::get('/report/group-sales-stores/export/{type}', 'ReportsController@groupSaleStoresExport')->name('report.group.sales.stores.export');
        Route::get('/report/group-sales-products', 'ReportsController@groupSaleProducts')->name('report.group.sales.products');
        Route::get('/report/group-sales-products/export/{type}', 'ReportsController@groupSaleProductsExport')->name('report.group.sales.products.export');
        Route::get('/report/group-sales-customers', 'ReportsController@groupSaleCustomers')->name('report.group.sales.customers');
        Route::get('/report/group-sales-customers/export/{type}', 'ReportsController@groupSaleCustomersExport')->name('report.group.sales.customers.export');
        Route::get('/report/coupon-usage', 'ReportsController@couponUsage')->name('report.coupon.usage');
        Route::get('/report/coupon-usage/export/{type}', 'ReportsController@couponUsageExport')->name('report.coupon.usage.export');
//    });


    // orders routes
    Route::get('/orders', 'OrdersController@index')->name('orders');
    Route::get('/orders/detail/{order}', 'OrdersController@detail')->name('orders.detail');
    Route::get('/orders/update-status/{order}/{status}', 'OrdersController@updateStatus')->name('orders.update.status');
    Route::get('/pending-orders', 'OrdersController@pendingOrders')->name('pending.orders');
    Route::get('/pending-orders/detail/{storeOrder}', 'OrdersController@pendingOrdersDetail')->name('pending.orders.detail');


    // return requests routes
    Route::get('/return-requests', 'ReturnRequestsController@index')->name('return.requests');
    Route::get('/return-requests/detail/{record}', 'ReturnRequestsController@detail')->name('return.requests.detail');
    Route::get('/return-requests/approve/{record}', 'ReturnRequestsController@approve')->name('return.requests.approve');
    Route::get('/return-requests/reject/{record}', 'ReturnRequestsController@reject')->name('return.requests.reject');
    Route::get('/return-requests/received-expected/{record}', 'ReturnRequestsController@receivedExpected')->name('return.requests.received.expected');
    Route::get('/return-requests/received-not-expected/{record}', 'ReturnRequestsController@receivedNotExpected')->name('return.requests.received.not.expected');
    Route::get('/return-requests/archive/{record}', 'ReturnRequestsController@archive')->name('return.requests.archive');

    // cancel requests routes
    Route::get('/cancel-requests', 'CancelRequestsController@index')->name('cancel.requests');

    // product reviews routes
    Route::get('/product-reviews', 'ProductReviewsController@index')->name('product.reviews');
    Route::get('/product-reviews/show/{item}', 'ProductReviewsController@show')->name('product.reviews.show');

    // crop media
    Route::get('/media/crop', 'MediaController@index')->name('media.crop');
    Route::post ('/media/crop', 'MediaController@cropImage');

    // store questions routes
    Route::get('/store-questions', 'StoreQuestionsController@index')->name('store.questions');
    Route::get('/store-questions/detail/{question}', 'StoreQuestionsController@detail')->name('store.questions.detail');
    Route::get('/store-questions/delete/{question}', 'StoreQuestionsController@delete')->name('store.questions.delete');
    Route::get('/store-questions/archive/{question}', 'StoreQuestionsController@archive')->name('store.questions.archive');
    Route::post('/store-questions/reply/{question}', 'StoreQuestionsController@reply')->name('store.questions.reply');
    Route::post('/store-questions/bulk-actions', 'StoreQuestionsController@bulkActions')->name('store.questions.bulk.actions');
    Route::get('/store-questions/view-archieved', 'StoreQuestionsController@viewArchived')->name('store.questions.view.archived');
    Route::get('/store-questions/view-archieved/detail/{question}', 'StoreQuestionsController@viewArchivedDetail')->name('store.questions.view.archived.detail');
});
