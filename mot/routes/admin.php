<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\HelpCenterControlle;

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

require __DIR__ . '/auth-admin.php';

Route::namespace('App\Http\Controllers\Admin')->group(function () {

    require __DIR__ . '/api-admin.php';

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UsersController');
    Route::resource('permission', 'PermissionController');


    Route::get('locale/{locale}', function ($locale){
        Session::put('locale_admin', $locale);
        return redirect()->back();
    });

    // pages routes
//    Route::get('/roles', 'RoleController@index')->name('roles');
//    Route::get('/roles/show', 'RoleController@show')->name('roles.show');
//    Route::get('/roles/add', 'RoleController@create')->name('roles.add');
//    Route::post('/roles/add', 'RoleController@store');
//    Route::get('/roles/edit/{page}', 'RoleController@edit')->name('roles.edit');
//    Route::post('/roles/edit/{page}', 'RoleController@update');
//    Route::get('/roles/delete/{page}', 'RoleController@delete')->name('roles.delete');

    // profile routes
    Route::get('/profile', 'ProfileController@showForm')->name('profile');
    Route::post('/profile', 'ProfileController@formProcess');

    // settings routes
    Route::get('/settings', 'SettingsController@showForm')->name('settings');
    Route::post('/settings', 'SettingsController@formProcess');

    // media settings routes
    Route::get('/media-settings', 'MediaSettingsController@showForm')->name('media.settings');
    Route::post('/media-settings', 'MediaSettingsController@formProcess');

    /*help centers routes */
    Route::get('/help-centers', 'HelpCenterController@index')->name('help-centers');
    Route::get('/help-centers/add', 'HelpCenterController@create')->name('help-centers.add');
    Route::post('/help-centers/add', 'HelpCenterController@store');
    Route::get('/help-centers/edit/{helpCenter}', 'HelpCenterController@edit')->name('help-centers.edit');
    Route::post('/help-centers/edit/{helpCenter}', 'HelpCenterController@update');
    Route::get('/help-centers/delete/{helpCenter}', 'HelpCenterController@delete')->name('help-centers.delete');

    // pages routes
    Route::get('/pages', 'PagesController@index')->name('pages');
    Route::get('/pages/add', 'PagesController@create')->name('pages.add');
    Route::post('/pages/add', 'PagesController@store');
    Route::get('/pages/edit/{page}', 'PagesController@edit')->name('pages.edit');
    Route::post('/pages/edit/{page}', 'PagesController@update');
    Route::get('/pages/delete/{page}', 'PagesController@delete')->name('pages.delete');

    // categories routes
    Route::get('/categories', 'CategoriesController@index')->name('categories');
    Route::get('/categories/add', 'CategoriesController@create')->name('categories.add');
    Route::post('/categories/add', 'CategoriesController@store');
    Route::get('/categories/edit/{category}', 'CategoriesController@edit')->name('categories.edit');
    Route::post('/categories/edit/{category}', 'CategoriesController@update');
    Route::get('/categories/delete/{category}', 'CategoriesController@delete')->name('categories.delete');
    Route::post('/categories/update/status', 'CategoriesController@updateStatus')->name('categories.update.status');
    Route::get('/categories/sorting', 'CategoriesController@sorting')->name('categories.sorting');
    Route::post('/categories/sorting/update', 'CategoriesController@updateSorting')->name('categories.sorting.update');
    
    // trendyol categories routes
    Route::get('/trendyol-categories', 'TrendyolCategoriesController@index')->name('trendyol.categories');
    Route::get('/trendyol-categories-parent', 'TrendyolCategoriesController@parentIndex')->name('trendyol.categories.parent');
    Route::get('/trendyol-categories/add', 'TrendyolCategoriesController@create')->name('trendyol.categories.add');
    Route::get('/trendyol-categories/assign/{trendyol}', 'TrendyolCategoriesController@assign')->name('trendyol.categories.assign');
    Route::post('/trendyol-categories/assign/{trendyol}', 'TrendyolCategoriesController@update');
    Route::get('/trendyol-products', 'TrendyolCategoriesController@getTrendyolProducts')->name('trendyol.products');
    Route::get('/trendyol-brand/add', 'TrendyolCategoriesController@createBrand')->name('trendyol.brand.add');
    Route::get('/google-translate', 'TrendyolCategoriesController@translate')->name('google.translate');
//    Route::post('/admin/all_categories', 'TrendyolCategoriesController@json_categories' )->name('json_categories');
    // brands routes
    Route::get('/brands', 'BrandsController@index')->name('brands');
    Route::get('/brands/add', 'BrandsController@create')->name('brands.add');
    Route::post('/brands/add', 'BrandsController@store');
    Route::get('/brands/edit/{brand}', 'BrandsController@edit')->name('brands.edit');
    Route::post('/brands/edit/{brand}', 'BrandsController@update');
    Route::get('/brands/delete/{brand}', 'BrandsController@delete')->name('brands.delete');
    Route::post('/brands/update/status', 'BrandsController@updateStatus')->name('brands.update.status');
    Route::get('/brands/sorting', 'BrandsController@sorting')->name('brands.sorting');
    Route::post('/brands/sorting/update', 'BrandsController@updateSorting')->name('brands.sorting.update');

    // stores routes
    Route::get('/stores', 'StoresController@index')->name('stores');
    Route::get('/stores/add', 'StoresController@create')->name('stores.add');
    Route::post('/stores/add', 'StoresController@store');
    Route::get('/stores/edit/{store}', 'StoresController@edit')->name('stores.edit');
    Route::post('/stores/edit/{store}', 'StoresController@update');
    Route::get('/stores/delete/{store}', 'StoresController@delete')->name('stores.delete');
    Route::post('/stores/update/status', 'StoresController@updateStatus')->name('stores.update.status');
    Route::get('/stores/approve/{store}', 'StoresController@approve')->name('stores.approve');
    Route::get('/stores/reject/{store}', 'StoresController@reject')->name('stores.reject');
    Route::get('/stores/request-submerchant/{store}', 'StoresController@requestSubmerchantOnIyzico')->name('stores.request-submerchant');
    Route::get('/stores/return-address/{store}', 'StoresController@returnAddress')->name('stores.return.address');
    Route::post('/stores/return-address/{store}', 'StoresController@returnAddressUpdate');

    // pending stores routes
    Route::get('/pending-stores', 'PendingStoresController@index')->name('pending.stores');
    Route::get('/rejected-stores', 'RejectedStoresController@index')->name('rejected.stores');

    // stores profile routes
    Route::get('/stores/profile/{store}', 'StoresProfileController@index')->name('stores.profile');
    // store profile routes
    Route::get('/stores/profile/{store}/add', 'StoresProfileController@showForm')->name('stores.profile.add');
    Route::post('/stores/profile/{store}/add', 'StoresProfileController@formProcess');
    Route::get('/stores/profile/{store}/edit/{item}', 'StoresProfileController@edit')->name('stores.profile.edit');
    Route::post('/stores/profile/{store}/edit/{item}', 'StoresProfileController@update');
    Route::get('/stores/profile/{store}/approve/{item}', 'StoresProfileController@approve')->name('stores.profile.approve');
    Route::get('/stores/profile/{store}/reject/{item}', 'StoresProfileController@reject')->name('stores.profile.reject');
    // store return address routes
    Route::get('/stores/return-address/{store}/add', 'StoresProfileController@showFormReturnAddress')->name('stores.return.address.add');
    Route::post('/stores/return-address/{store}/add', 'StoresProfileController@storeReturnAddress');

    // stores staff routes
    Route::get('/stores/{store}/staff', 'StoresStaffController@index')->name('stores.staff');
    Route::get('/stores/{store}/staff/add', 'StoresStaffController@create')->name('stores.staff.add');
    Route::post('/stores/{store}/staff/add', 'StoresStaffController@store');
    Route::get('/stores/{store}/staff/edit/{staff}', 'StoresStaffController@edit')->name('stores.staff.edit');
    Route::post('/stores/{store}/staff/edit/{staff}', 'StoresStaffController@update');
    Route::get('/stores/{store}/staff/delete/{staff}', 'StoresStaffController@delete')->name('stores.staff.delete');
    Route::post('/stores/{store}/staff/update/status', 'StoresStaffController@updateStatus')->name('stores.staff.update.status');

    // countries routes
    Route::get('/countries', 'CountriesController@index')->name('countries');
    Route::get('/countries/add', 'CountriesController@create')->name('countries.add');
    Route::post('/countries/add', 'CountriesController@store');
    Route::get('/countries/edit/{country}', 'CountriesController@edit')->name('countries.edit');
    Route::post('/countries/edit/{country}', 'CountriesController@update');
    Route::get('/countries/delete/{country}', 'CountriesController@delete')->name('countries.delete');
    Route::post('/countries/update/status', 'CountriesController@updateStatus')->name('countries.update.status');
    Route::get('/countries/set-default/{country}', 'CountriesController@setDefault')->name('countries.set.default');

    // ShippingRate routes
    Route::get('/countries/{country}/shipping', 'ShippingRateController@index')->name('shipping.rates');
    Route::get('/countries/{country}/shipping/add', 'ShippingRateController@create')->name('shipping.rates.add');
    Route::post('/countries/{country}/shipping/add', 'ShippingRateController@store');
    Route::get('/countries/{country}/shipping/edit/{shippingRate}', 'ShippingRateController@edit')->name('shipping.rates.edit');
    Route::post('/countries/{country}/shipping/edit/{shippingRate}', 'ShippingRateController@update');
    Route::get('/countries/{country}/shipping/delete/{shippingRate}', 'ShippingRateController@delete')->name('shipping.rates.delete');
    Route::post('/countries/{country}/shipping/update/status', 'ShippingRateController@updateStatus')->name('shipping.rates.update.status');

    // State routes
    Route::get('/countries/{country}/states', 'StatesController@index')->name('states');
    Route::get('/countries/{country}/states/add', 'StatesController@create')->name('states.add');
    Route::post('/countries/{country}/states/add', 'StatesController@store');
    Route::get('/countries/{country}/states/edit/{state}', 'StatesController@edit')->name('states.edit');
    Route::post('/countries/{country}/states/edit/{state}', 'StatesController@update');
    Route::get('/countries/{country}/states/delete/{state}', 'StatesController@delete')->name('states.delete');
    Route::post('/countries/{country}/states/update/status', 'StatesController@updateStatus')->name('states.update.status');

    // cities routes
    Route::get('/countries/{country}/states/{state}/cities', 'CitiesController@index')->name('cities');
    Route::get('/countries/{country}/states/{state}/cities/add', 'CitiesController@create')->name('cities.add');
    Route::post('/countries/{country}/states/{state}/cities/add', 'CitiesController@store');
    Route::get('/countries/{country}/states/{state}/cities/edit/{city}', 'CitiesController@edit')->name('cities.edit');
    Route::post('/countries/{country}/states/{state}/cities/edit/{city}', 'CitiesController@update');
    Route::get('/countries/{country}/states/{state}/cities/delete/{city}', 'CitiesController@delete')->name('cities.delete');
    Route::post('/countries/{country}/states/{state}/cities/update/status', 'CitiesController@updateStatus')->name('cities.update.status');


    // contact inquiry routes
    Route::get('/contact-inquiries', 'ContactInquiriesController@index')->name('contact.inquiries');
    Route::get('/contact-inquiries/detail/{inquiry}', 'ContactInquiriesController@detail')->name('contact.inquiries.detail');
    Route::get('/contact-inquiries/delete/{inquiry}', 'ContactInquiriesController@delete')->name('contact.inquiries.delete');
    Route::get('/contact-inquiries/archive/{inquiry}', 'ContactInquiriesController@archive')->name('contact.inquiries.archive');
    Route::post('/contact-inquiries/reply/{inquiry}', 'ContactInquiriesController@reply')->name('contact.inquiries.reply');
    Route::post('/contact-inquiries/bulk-actions', 'ContactInquiriesController@bulkActions')->name('contact.inquiries.bulk.actions');
    Route::get('/contact-inquiries/view-archieved', 'ContactInquiriesController@viewArchived')->name('contact.inquiries.view.archived');
    Route::get('/contact-inquiries/view-archieved/detail/{inquiry}', 'ContactInquiriesController@viewArchivedDetail')->name('contact.inquiries.view.archived.detail');

    Route::get('/activity-logs', 'LogActivityController@index')->name('activity.logs');

    // customers routes
    Route::get('/customers', 'CustomersController@index')->name('customers');
    Route::get('/customers/add', 'CustomersController@create')->name('customers.add');
    Route::post('/customers/add', 'CustomersController@store');
    Route::get('/customers/edit/{customer}', 'CustomersController@edit')->name('customers.edit');
    Route::post('/customers/edit/{customer}', 'CustomersController@update');
    Route::get('/customers/delete/{customer}', 'CustomersController@delete')->name('customers.delete');
    Route::post('/customers/update/status', 'CustomersController@updateStatus')->name('customers.update.status');
    Route::post('/customers/deleted/all', 'CustomersController@deleteAll')->name('customers.deleted.all');
    Route::post('/customers/status/all', 'CustomersController@statusAll')->name('customers.status.all');

    // customer addresses routes
    Route::get('/customers/{customer}/addresses', 'CustomersAddressesController@index')->name('addresses');
    Route::get('/customers/{customer}/addresses/add', 'CustomersAddressesController@create')->name('addresses.add');
    Route::post('/customers/{customer}/addresses/add', 'CustomersAddressesController@store');
    Route::get('/customers/{customer}/addresses/edit/{address:id}', 'CustomersAddressesController@edit')->name('addresses.edit');
    Route::post('/customers/{customer}/addresses/edit/{address:id}', 'CustomersAddressesController@update');
    Route::get('/customers/{customer}/addresses/delete/{address:id}', 'CustomersAddressesController@delete')->name('addresses.delete');
    Route::get('/customers/{customer}/addresses/default/{address:id}', 'CustomersAddressesController@makeDefault')->name('addresses.default');


    // currencies routes
    Route::get('/currencies', 'CurrenciesController@index')->name('currencies');
    Route::get('/currencies/add', 'CurrenciesController@create')->name('currencies.add');
    Route::post('/currencies/add', 'CurrenciesController@store');
    Route::get('/currencies/edit/{currency}', 'CurrenciesController@edit')->name('currencies.edit');
    Route::post('/currencies/edit/{currency}', 'CurrenciesController@update');
    Route::get('/currencies/delete/{currency}', 'CurrenciesController@delete')->name('currencies.delete');
    Route::post('/currencies/update/status', 'CurrenciesController@updateStatus')->name('currencies.update.status');
    Route::get('/currencies/set-default/{currency}', 'CurrenciesController@setDefault')->name('currencies.set.default');

    // languages routes
    Route::get('/languages', 'LanguagesController@index')->name('languages');
    Route::get('/languages/add', 'LanguagesController@create')->name('languages.add');
    Route::post('/languages/add', 'LanguagesController@store');
    Route::get('/languages/edit/{language}', 'LanguagesController@edit')->name('languages.edit');
    Route::post('/languages/edit/{language}', 'LanguagesController@update');
    Route::get('/languages/delete/{language}', 'LanguagesController@delete')->name('languages.delete');
    Route::post('/languages/update/status', 'LanguagesController@updateStatus')->name('languages.update.status');
    Route::get('/languages/set-default/{language}', 'LanguagesController@setDefault')->name('languages.set.default');

    // Translation routes
    Route::get('/languages/{language}/translation', 'TranslationController@index')->name('translation');
    Route::get('/languages/{language}/translation/add', 'TranslationController@create')->name('translation.add');
    Route::post('/languages/{language}/translation/add', 'TranslationController@store');
    Route::get('/languages/{language}/translation/edit/{translate}', 'TranslationController@edit')->name('translation.edit');
    Route::post('/languages/{language}/translation/edit/{translate}', 'TranslationController@update');
    Route::get('/languages/{language}/translation/delete/{translate}', 'TranslationController@destroy')->name('translation.delete');
    Route::post('/languages/{language}/translation/update/status', 'TranslationController@updateStatus')->name('translation.update.status');

    // attributes routes
    Route::get('/attributes', 'AttributesController@index')->name('attributes');
    Route::get('/attributes/add', 'AttributesController@create')->name('attributes.add');
    Route::post('/attributes/add', 'AttributesController@store');
    Route::get('/attributes/edit/{attribute}', 'AttributesController@edit')->name('attributes.edit');
    Route::post('/attributes/edit/{attribute}', 'AttributesController@update');
    Route::get('/attributes/delete/{attribute}', 'AttributesController@delete')->name('attributes.delete');
    Route::get('/attributes/sorting', 'AttributesController@sorting')->name('attributes.sorting');
    Route::post('/attributes/sorting/update', 'AttributesController@updateSorting')->name('attributes.sorting.update');

    // attribute options routes
    Route::get('/attributes/{attribute}/options', 'AttributesOptionsController@index')->name('attributes.options');
    Route::get('/attributes/{attribute}/options/add', 'AttributesOptionsController@create')->name('attributes.options.add');
    Route::post('/attributes/{attribute}/options/add', 'AttributesOptionsController@store');
    Route::get('/attributes/{attribute}/options/edit/{option}', 'AttributesOptionsController@edit')->name('attributes.options.edit');
    Route::post('/attributes/{attribute}/options/edit/{option}', 'AttributesOptionsController@update');
    Route::get('/attributes/{attribute}/options/sorting', 'AttributesOptionsController@sorting')->name('attributes.options.sorting');

    // products routes
    Route::get('/products', 'ProductsController@index')->name('products');
    Route::get('/products/add', 'ProductsController@create')->name('products.add.show');
    Route::post('/products/add', 'ProductsController@store')->name('products.add');
    Route::get('/products/edit/{product}', 'ProductsController@edit')->name('products.edit');
    Route::post('/products/edit/{product}', 'ProductsController@update');
    Route::post('/products/deleted/all', 'ProductsController@deleteAll')->name('products.deleted.all');
    Route::post('/products/approved/all', 'ProductsController@approveAll')->name('products.approved.all');
    Route::get('/products/delete/{product}', 'ProductsController@delete')->name('products.delete');
    Route::post('get-products', 'ProductsController@getProducts')->name('get-products');
    Route::post('/products/update/status', 'ProductsController@updateStatus')->name('products.update.status');
    Route::post('/products/gallery/upload', 'ProductsController@galleryUpload')->name('products.gallery.upload');
    Route::post('/products/gallery/delete', 'ProductsController@galleryDelete')->name('products.gallery.delete');
    Route::post('/products/gallery/update-order', 'ProductsController@galleryUpdateOrder')->name('products.gallery.update.order');
    Route::get('/products/approve/{product}', 'ProductsController@approve')->name('products.approve');
    Route::get('/products/delete-garbage', 'ProductsController@deleteGarbage');


    // import products routes
    Route::get('/products/download-sample-excel', 'ProductsController@downloadSampleExcel')->name('products.download-sample-excel');
    Route::post('/products/import', 'ProductsController@import')->name('products.import');
    Route::post('/import-images-zip', 'ProductsController@importImagesZip')->name('import-images-zip');

    // pending products routes
    Route::get('/pending-products', 'PendingProductsController@index')->name('pending.products');

    // pending brands routes
    Route::get('/pending-brands', 'PendingBrandsController@index')->name('pending.brands');
    Route::get('/brands/approve/{brand}', 'BrandsController@approve')->name('brands.approve');

    // tags routes
    Route::get('/tags', 'TagsController@index')->name('tags');
    Route::get('/tags/add', 'TagsController@create')->name('tags.add');
    Route::post('/tags/add', 'TagsController@store');
    Route::get('/tags/edit/{tag}', 'TagsController@edit')->name('tags.edit');
    Route::post('/tags/edit/{tag}', 'TagsController@update');
    Route::get('/tags/delete/{tag}', 'TagsController@delete')->name('tags.delete');
    Route::post('/tags/update/status', 'TagsController@updateStatus')->name('tags.update.status');

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

    // request product routes
    Route::get('/request-products', 'RequestProductController@index')->name('request.products');
    Route::get('/request-products/detail/{requestProduct}', 'RequestProductController@detail')->name('request.products.detail');
    Route::get('/request-products/delete/{requestProduct}', 'RequestProductController@delete')->name('request.products.delete');
    Route::get('/request-products/archive/{requestProduct}', 'RequestProductController@archive')->name('request.products.archive');
    Route::post('/request-products/reply/{requestProduct}', 'RequestProductController@reply')->name('request.products.reply');
    Route::post('/request-products/bulk-actions', 'RequestProductController@bulkActions')->name('request.products.bulk.actions');
    Route::get('/request-products/view-archieved', 'RequestProductController@viewArchived')->name('request.products.view.archived');
    Route::get('/request-products/view-archieved/detail/{requestProduct}', 'RequestProductController@viewArchivedDetail')->name('request.products.view.archived.detail');


    // home page routes
//    Route::prefix('home-page')->group(function () {

        // sliders routes
        Route::get('/home-page/sliders', 'SlidersController@index')->name('sliders');
        Route::get('/home-page/sliders/add', 'SlidersController@create')->name('sliders.add');
        Route::post('/home-page/sliders/add', 'SlidersController@store');
        Route::get('/home-page/sliders/edit/{slider}', 'SlidersController@edit')->name('sliders.edit');
        Route::post('/home-page/sliders/edit/{slider}', 'SlidersController@update');
        Route::get('/home-page/sliders/delete/{slider}', 'SlidersController@delete')->name('sliders.delete');
        Route::get('/home-page/sliders/sorting', 'SlidersController@sorting')->name('sliders.sorting');
        Route::post('/home-page/sliders/sorting/update', 'SlidersController@updateSorting')->name('sliders.sorting.update');
        Route::post('/home-page/sliders/update/status', 'SlidersController@updateStatus')->name('sliders.update.status');

        // sponsored categories routes
        Route::get('/home-page/sponsored-categories', 'SponsoredCategoriesController@index')->name('sponsored.categories');
        Route::get('/home-page/sponsored-categories/add', 'SponsoredCategoriesController@create')->name('sponsored.categories.add');
        Route::post('/home-page/sponsored-categories/add', 'SponsoredCategoriesController@store');
        Route::get('/home-page/sponsored-categories/edit/{item}', 'SponsoredCategoriesController@edit')->name('sponsored.categories.edit');
        Route::post('/home-page/sponsored-categories/edit/{item}', 'SponsoredCategoriesController@update');
        Route::get('/home-page/sponsored-categories/delete/{item}', 'SponsoredCategoriesController@delete')->name('sponsored.categories.delete');
        Route::post('/home-page/sponsored-categories/update/status', 'SponsoredCategoriesController@updateStatus')->name('sponsored.categories.update.status');

        // tabbed products routes
        Route::get('/home-page/tabbed-products', 'TabbedProductsController@index')->name('tabbed.products');
        Route::get('/home-page/tabbed-products/add', 'TabbedProductsController@create')->name('tabbed.products.add');
        Route::post('/home-page/tabbed-products/add', 'TabbedProductsController@store');
        Route::get('/home-page/tabbed-products/edit/{item}', 'TabbedProductsController@edit')->name('tabbed.products.edit');
        Route::post('/home-page/tabbed-products/edit/{item}', 'TabbedProductsController@update');
        Route::get('/home-page/tabbed-products/delete/{item}', 'TabbedProductsController@delete')->name('tabbed.products.delete');
        Route::post('/home-page/tabbed-products/update/status', 'TabbedProductsController@updateStatus')->name('tabbed.products.update.status');

        // trending products routes
        Route::get('/trending-products', 'TrendingProductsController@index')->name('trending.products');
        Route::get('/trending-products/add', 'TrendingProductsController@create')->name('trending.products.add');
        Route::post('/trending-products/add', 'TrendingProductsController@store');
        Route::get('/trending-products/edit/{item}', 'TrendingProductsController@edit')->name('trending.products.edit');
        Route::post('/trending-products/edit/{item}', 'TrendingProductsController@update');
        Route::get('/trending-products/delete/{item}', 'TrendingProductsController@delete')->name('trending.products.delete');
        Route::post('/trending-products/update/status', 'TrendingProductsController@updateStatus')->name('trending.products.update.status');

        // banners routes
        Route::get('/home-page/banners', 'BannersController@index')->name('banners');
        Route::get('/home-page/banners/add', 'BannersController@create')->name('banners.add');
        Route::post('/home-page/banners/add', 'BannersController@store');
        Route::get('/home-page/banners/edit/{banner}', 'BannersController@edit')->name('banners.edit');
        Route::post('/home-page/banners/edit/{banner}', 'BannersController@update');
        Route::get('/home-page/banners/delete/{banner}', 'BannersController@delete')->name('banners.delete');
        Route::post('/home-page/banners/update/status', 'BannersController@updateStatus')->name('banners.update.status');

        // sections sorting routes
        Route::get('/home-page/sections-sorting', 'HomePageSectionsController@index')->name('sections.sorting');
        Route::post('/home-page/sections-sorting', 'HomePageSectionsController@sort')->name('sections.sorting.update');
//    });

    // promotions routes
//    Route::prefix('promotions')->group(function () {

        // coupons routes
        Route::get('/promotions/coupons', 'CouponsController@index')->name('coupons');
        Route::get('/promotions/coupons/add', 'CouponsController@create')->name('coupons.add');
        Route::post('/promotions/coupons/add', 'CouponsController@store');
        Route::get('/promotions/coupons/edit/{coupon}', 'CouponsController@edit')->name('coupons.edit');
        Route::post('/promotions/coupons/edit/{coupon}', 'CouponsController@update');
        Route::get('/promotions/coupons/delete/{coupon}', 'CouponsController@delete')->name('coupons.delete');
        Route::post('/promotions/coupons/update/status', 'CouponsController@updateStatus')->name('coupons.update.status');

        // free delivery products routes
        Route::get('/promotions/free-delivery', 'FreeDeliveryController@index')->name('free.delivery');
        Route::get('/promotions/free-delivery/add', 'FreeDeliveryController@create')->name('free.delivery.add');
        Route::post('/promotions/free-delivery/add', 'FreeDeliveryController@store');

        // bundled products routes
        Route::get('/promotions/bundled-products', 'BundledProductsController@index')->name('bundled.products');
//    });

    // reports routes
//    Route::prefix('reports')->group(function () {

        // purchase routes
        Route::get('/reports/sales', 'ReportsController@sales')->name('reports.sales');
        Route::get('/reports/sales/export/{type}', 'ReportsController@export')->name('reports.sales.export');
        Route::get('/reports/group-sale', 'ReportsController@groupSales')->name('reports.group.sale');
        Route::get('/reports/group-sale/export/{type}', 'ReportsController@groupExport')->name('reports.group.sale.export');
        Route::get('/reports/group-sales-stores', 'ReportsController@groupSaleStores')->name('reports.group.sales.stores');
        Route::get('/reports/group-sales-stores/export/{type}', 'ReportsController@groupSaleStoresExport')->name('reports.group.sales.stores.export');
        Route::get('/reports/group-sales-products', 'ReportsController@groupSaleProducts')->name('reports.group.sales.products');
        Route::get('/reports/group-sales-products/export/{type}', 'ReportsController@groupSaleProductsExport')->name('reports.group.sales.products.export');
        Route::get('/reports/group-sales-customers', 'ReportsController@groupSaleCustomers')->name('reports.group.sales.customers');
        Route::get('/reports/group-sales-customers/export/{type}', 'ReportsController@groupSaleCustomersExport')->name('reports.group.sales.customers.export');
        Route::get('/reports/coupon-usage', 'ReportsController@couponUsage')->name('reports.coupon.usage');
        Route::get('/reports/coupon-usage/export/{type}', 'ReportsController@couponUsageExport')->name('reports.coupon.usage.export');
        Route::get('/reports/most-searches', 'ReportsController@mostSearches')->name('reports.most-searches');

//    });

    // orders routes
    Route::get('/orders', 'OrdersController@index')->name('orders');
    Route::get('/orders/detail/{order}', 'OrdersController@detail')->name('orders.detail');
    Route::get('/orders/overview/{order}', 'OrdersController@overview')->name('orders.overview');
    Route::get('/orders/detail/export/{order}', 'OrdersController@exportDetail')->name('orders.detail.export');
    Route::get('/orders/update-status/{storeOrder}/{status}', 'OrdersController@updateStatus')->name('orders.update.status');
    Route::get('/pending-orders', 'OrdersController@pendingOrders')->name('pending.orders');
    Route::get('/pending-orders/detail/{order}', 'OrdersController@pendingOrdersDetail')->name('pending.orders.detail');
    Route::get('/orders/update-order-from/{order}/{status}', 'OrdersController@updateOrderFrom')->name('orders.update.whatsapp');
    
    // DHL Services
    Route::post('/pickup-request/{storeOrder}', 'DhlController@pickUpRequest')->name('pickup.request');
    Route::post('/shipment-request/{storeOrder}', 'DhlController@shipmentRequest')->name('shipment.request');
    Route::get('/validate-request/{storeOrder}', 'DhlController@rateRequestForValidateStoreAddress')->name('validate.request');
    
    // daily deals routes
    Route::get('/daily-deals', 'DailyDealsController@index')->name('daily.deals');
    Route::get('/daily-deals/delete/{deal}', 'DailyDealsController@delete')->name('daily.deals.delete');
    Route::post('/daily-deals/update/status', 'DailyDealsController@updateStatus')->name('daily.deals.update.status');
    Route::get('/daily-deals/approve/{deal}', 'DailyDealsController@approve')->name('daily.deals.approve');

    // flash deals routes
    Route::get('/flash-deals', 'FlashDealsController@index')->name('flash.deals');
    Route::get('/flash-deals/edit/{deal}/store/{store}', 'FlashDealsController@edit')->name('flash.deals.edit');
    Route::post('/flash-deals/edit/{deal}/store/{store}', 'FlashDealsController@update');
    Route::get('/flash-deals/delete/{deal}', 'FlashDealsController@delete')->name('flash.deals.delete');
    Route::post('/flash-deals/update/status', 'FlashDealsController@updateStatus')->name('flash.deals.update.status');
    Route::get('/flash-deals/approve/{deal}', 'FlashDealsController@approve')->name('flash.deals.approve');
    

    Route::get('/flash-deals/sorting', 'FlashDealsController@sorting')->name('flash.sorting');
    Route::post('/flash-deals/sorting/update', 'FlashDealsController@updateSorting')->name('flash.sorting.update');


    // product reviews routes
    Route::get('/product-reviews', 'ProductReviewsController@index')->name('product.reviews');
    Route::get('/product-reviews/show/{item}', 'ProductReviewsController@show')->name('product.reviews.show');
    Route::get('/product-reviews/approve/{item}', 'ProductReviewsController@approve')->name('product.reviews.approve');
    Route::get('/product-reviews/reject/{item}', 'ProductReviewsController@reject')->name('product.reviews.reject');

    // crop media
    Route::get('/media/crop', 'MediaController@index')->name('media.crop');
    Route::post ('/media/crop', 'MediaController@cropImage');

    // product banners routes
    Route::get('/product-banners', 'ProductBannersController@index')->name('product.banners');
    Route::get('/product-banners/add', 'ProductBannersController@create')->name('product.banners.add');
    Route::post('/product-banners/add', 'ProductBannersController@store');
    Route::get('/product-banners/edit/{productBanner}', 'ProductBannersController@edit')->name('product.banners.edit');
    Route::post('/product-banners/edit/{productBanner}', 'ProductBannersController@update');
    Route::get('/product-banners/delete/{productBanner}', 'ProductBannersController@delete')->name('product.banners.delete');
    Route::post('/product-banners/update/status', 'ProductBannersController@updateStatus')->name('product.banners.update.status');
});
