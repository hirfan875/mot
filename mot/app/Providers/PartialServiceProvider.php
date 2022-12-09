<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Brand;

class PartialServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['web.partials.top-header', 'web.partials.header'], function($view){
            $header_contact_no = get_option('contact_no');
            $header_email = get_option('targetemail');

            $view->with('header_contact_no', $header_contact_no);
            $view->with('header_email', $header_email);
        });

        View::composer(['web.partials.footer'], function($view){
            $brands = Brand::where('status', true)->where('store_id', null)->limit(20)->get();
            $top_brands = Collect($brands);
            $footer_contact_no = get_option('contact_no');
            $footer_email = get_option('targetemail');

            $view->with('top_brands', $top_brands);
            $view->with('footer_contact_no', $footer_contact_no);
            $view->with('footer_email', $footer_email);
        });
    }
}
