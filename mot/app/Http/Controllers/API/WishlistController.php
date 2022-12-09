<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Resources\Customer as CustomerResource;
use App\Extensions\Response;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Wishlist;
use App\Models\Product;
use App\Service\WishlistService;


class WishlistController extends BaseController
{

    public function index(Request $request)
    {
        if (!Auth('sanctum')->check()) {
            $this->sendError(__('User not found'), []);
        }

        try {
            $wishlistProductIds = Wishlist::where('customer_id', Auth()->user()->id)->orderBy('id', 'desc')->pluck('product_id')->toArray();
            $products = Product::whereIn('id', $wishlistProductIds);
            if ($wishlistProductIds) {
                $wishlistProductIds = implode(',', $wishlistProductIds);
                $products = $products->orderByRaw("FIELD(id, $wishlistProductIds)");
            }
            $products = $products->get();
            $products = ProductResource::collection($products);
        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }

        return $this->sendResponse($products, __('Data loaded successfully'));
    }


    public function addWishlist(Request $request,$id){
        try {

            $customer = Customer::whereId(Auth()->user()->id)->first();
            $wishlistService = new WishlistService();
            $wishlistService->add($customer, $id);
            $count = $wishlistService->count($customer);
            $product = Product::whereId($id)->first();
        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }

        $success['customer'] = $customer;
        $success['product'] = $product;
        $success['count'] = $count;

        return $this->sendResponse($success, __('Product has been added to wishlist'));
    }

    public function wishlistCount(Request $request)
    {
        try {
            $wishListCount = 0;
            if (Auth()->user()) {
                $wishListService = new WishlistService();
                $wishlist = $wishListService->getCustomerWishlist(Auth()->user()->id);
                $wishListCount = $wishlist->count();
            }

            $success['wishListCount'] = $wishListCount;

            return $this->sendResponse($success, __('wishList Count'));
        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }
    }

    /**
     * @param $productId
     * @return \Illuminate\Http\Response
     */
    public function destroyWishlist($productId)
    {
        if (!Auth('sanctum')->check()) {
            return $this->sendError(__('User not found'));
        }

        try {
            $customer = Auth('sanctum')->user();;

            $wishlistService = new WishlistService();
            $deleted = $wishlistService->remove($customer, $productId);
            if (!$deleted) {
                return $this->sendError(__('Unable to deleted'));
            }
        } catch (\Exception $exc) {
            return $this->sendError(__($exc->getMessage()));
        }

        return $this->sendResponse([], __('Item has been removed from wishlist'));
    }

}
