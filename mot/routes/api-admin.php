<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->name('api.')->group(function(){

    Route::get('/products/select2', 'ApiController@getProductsForSelect2')->name('products.select2');
    Route::post('/get-mot-commission', 'ApiController@getMotCommission')->name('get.mot.commission');
});
