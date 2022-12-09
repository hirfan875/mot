<?php

namespace App\Providers;

use App\Models\Customer;
use App\Service\FilterCategoryService;
use App\Service\WishlistService;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Service\MoTCartService;
use App\Helpers\UtilityHelpers;

class ViewServiceProvider extends ServiceProvider
{
    const HOME_PAGE_CATEGORY_LIMIT = 25;
    /**
     * @var Customer
     */
    protected $customer;


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('web.partials.header', function ($view) {
            $wishListCount = 0;
            if (Auth::guard('customer')->user()){
                $logger = getLogger('*');
//                $logger->debug('user is  ' . Auth::guard('customer')->user());
                $wishListService = new WishlistService();
                $wishlist = $wishListService->getCustomerWishlist(Auth::guard('customer')->user()->id);
                $wishListCount = $wishlist->count();
            }
            View::share(
                ['wishListCount' => $wishListCount]
            );
        });

        View::composer('web.partials.all-categories', function ($view) {
            $filterCategoryService = new FilterCategoryService();
            $headerCategories = $filterCategoryService->active()->take(50)->get();
            $headerNavcategories = $filterCategoryService->withSubcategories()->active()->take(self::HOME_PAGE_CATEGORY_LIMIT)->get();
            View::share(
                [
                    'headerCategories' => $headerCategories,
                    'headerNavcategories'=> $headerNavcategories
                ]
            );
        });
        
        View::composer('web.partials.header', function ($view) {
            $filterCategoryService = new FilterCategoryService();
            $headerCategories = $filterCategoryService->active()->take(50)->get();
            $headerNavcategories = $filterCategoryService->withSubcategories()->active()->take(self::HOME_PAGE_CATEGORY_LIMIT)->get();

            View::share(
                [
                    'headerCategories' => $headerCategories,
                    'headerNavcategories'=> $headerNavcategories
                ]
            );
        });

        View::composer('web.partials.header', function ($view) {
//            $logger = getLogger('web.partials.header');
//            $logger->debug('getting cart items count');
            $cartCount=0;
            $topCartItems= '';
            $cartSubtotal = 0;
            $cartTotal = 0;

            if(UtilityHelpers::getCartSessionId()){
                $cartService = new MoTCartService(UtilityHelpers::getCartSessionId());
                $cartCount = $cartService->TotalQuantity();
                $topCartItems = $cartService->getCartListItems();
                $cartSubtotal = $cartService->getSubTotal();
                $cartTotal = $cartService->getTotal();
//                couponDiscount();
            }

            View::share(
                [
                    'cartCount'     => $cartCount,
                    'topCartItems'  => $topCartItems,
                    'cartSubtotal' => $cartSubtotal,
                    'cartTotal' => $cartTotal
                ]
            );
        });

        View::composer('web.partials.footer', function ($view) {
            
            $searchableProducts = '';
            $data = array();
            $searchableProducts = \App\Models\Product::with('product_translates')->whereNotNull('title')->where('status', true)->whereNull('parent_id')->where('is_approved', true)->get();
            foreach($searchableProducts as $key => $val) {
                $data[$key]['title'] = ($val->product_translates) ? $val->product_translates->title : $val->title;
                 $data[$key]['slug'] =  $val->slug;
            }
            View::share(['searchableProducts' => json_encode($data)]);
        });
    }
}
