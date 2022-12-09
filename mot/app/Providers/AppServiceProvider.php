<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use DB;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        $this->app->register(ViewServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ( Schema::hasTable('settings') ) {
            config(['media.placeholder' => get_option('media_placeholder')]);
        }

        Paginator::useBootstrap();

        \Validator::extend('phone_number', function($attribute, $value, $parameters)
        {
            /** Figure out an acceptable format */
            $value = str_replace([' ', '.', '-'] , "" , $value);
            return preg_match('/[0-9]{10}/', $value);
        });
        Schema::defaultStringLength(191);
        
//        DB::listen(function($query) {
//            Log::info(
//                $query->sql,
//                $query->bindings,
//                $query->time
//            );
//        });
    }
}
