<?php

namespace App\Service;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
use App\Exceptions\InvalidCouponException;
use phpDocumentor\Reflection\Types\Null_;
use Carbon;
use App\Extensions\Response;

class CouponDiscountService
{
    /**
     * check coupon validity & return discount
     *
     * @param string $coupon_code
     * @param Cart $cart
     * @return Cart
     * @throws \Exception
     */
    public function validateCoupon($coupon_code, Cart $cart)
    {
        // if coupon code empty return
//        if (empty($coupon_code)) {
//            throw new \Exception(__('Please provide coupon code.'));
//        }
        
        $today_date = Carbon\Carbon::now()->toDateString();
        
        if($coupon_code){
        /** @var Coupon $coupon */
        $coupon = Coupon::whereStatus(true)
            ->whereCouponCode(strtoupper(trim($coupon_code)))
                ->whereDate('start_date', '<=', $today_date)->whereDate('end_date', '>=', $today_date)
            ->where(function ($query) {
                $query
                    ->whereHas('store', function ($query) {
                        $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
                    })
                    ->orWhereNull('store_id');
            })
            ->first();
            
            // if invalid coupon return
            if (!$coupon) {
                throw new InvalidCouponException(__('Invalid coupon code.'));
            }

            // check coupon expiry
            $this->checkCouponExpiry($coupon);
            
            // check coupon total usage
            $this->checkCouponTotalUsage($coupon, $cart->customer);
           
        } else {
            $coupon = Coupon::whereStatus(true)
                    ->whereNull('coupon_code')
                    ->whereDate('start_date', '<=', $today_date)->whereDate('end_date', '>=', $today_date)
            ->where(function ($query) {
                $query
                    ->whereHas('store', function ($query) {
                        $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
                    })
                    ->orWhereNull('store_id');
            })
            ->first();
           if($coupon){
            // check coupon total usage
            $this->checkCouponTotalUsage($coupon, $cart->customer);
           }  
        }    
        
       return $coupon; 
    }

    /**
     * get discounted amount
     *
     * @param float $total
     * @param int/float $discount
     * @param string $type
     * @return float
     */
    public function getDiscountedAmount(float $total, ?int $discount, string $type)
    {
        if ($type === 'fixed') {
            return $discount;
        }
        
        if ($type === 'get_free') {
            return $discount;
        }

        $discounted_amount = $total * ($discount / 100);
        return $discounted_amount;
    }

    /**
     * get success response
     * @tahir Cart should be updated and returned. .. The success response is job of controller
     *
     * @param Coupon $coupon
     * @param Cart $cart
     * @param float $discounted_amount
     * @return array
     */
    private function getSuccessResponse(Coupon $coupon, Cart $cart, float $discounted_amount, $productIds = [], $cartProductId = '', $cartitemId = ''): array
    {
        $cart->recalculate();
        // update cart total
        $cart->coupon_id = $coupon->id;
        $cart->total = $cart->sub_total - $discounted_amount;
        $cart->save();

        $cartProducts = $cart->cart_products;
        /*check if discount applied on specific product*/
        if(count($productIds) > 0) {
            $cartProducts = $cart->cart_products->whereIn('product_id', $productIds);
        } 
        $cartProductsitems = $cart->cart_products;
        foreach ($cartProductsitems as $item) {
            $item->discounted_at = null;
            $item->discount = null;
            $item->save();
        }
            
        if($discounted_amount > 0){
            $discountPerItem = $discounted_amount / $cartProducts->sum('quantity');
        } else {
            $discountPerItem = 0;
        }
        
        foreach ($cartProducts as $item) {
            
            if($coupon->type == 'percentage') {
                $itemSubtotal =  $item->quantity * $item->unit_price;
                $item->discount = $itemSubtotal * ($coupon->discount / 100);
            } else {
                $item->discount = $item->quantity * $discountPerItem;
            }
            
            if($cartitemId == $item->id){
                $item->discounted_at = 'Free';
            } else {
                $item->discounted_at = null;
            }
            
            $item->save();
        }
        
//        $coupon->discount =  $discounted_amount;
        $coupon->save();
        
        if ($cart->order_id != null) {
            $order = Order::find($cart->order_id);
            $order->coupon_id = $coupon->id;
            $order->discount = $coupon->discount;
            $order->discount_type = $coupon->type;
            $order->save();

            $orderItems = $order->order_items()->whereIn('product_id', $cartProducts->pluck('product_id'))->get();
            foreach($orderItems as $orderItem){
                if($coupon->type == 'percentage') {
                    $orderItemSubtotal =  $orderItem->quantity * $orderItem->unit_price;
                    $orderItem->discount = $orderItemSubtotal * ($coupon->discount / 100);
                } else {
                    $orderItem->discount = $orderItem->quantity * $discountPerItem;
                }
                if($cartProductId == $orderItem->product_id){
                    $orderItem->discounted_at = 'Free';
                } else {
                    $orderItem->discounted_at = null;
                }
                $orderItem->save();
            }
        }

        return [
            'success' => true,
            'id' => $coupon->id,
            'coupon_code' => $coupon->coupon_code,
            'type' => $coupon->type,
            'discount' => $coupon->discount,
            'discounted_amount' => $discounted_amount,
        ];
    }
    
    
    private function getErrorResponse(Coupon $coupon, Cart $cart, float $discounted_amount, $productIds = [], $cartProductId = '', $cartitemId = ''): array
    {
        $cart->recalculate();
        // update cart total
        $cart->coupon_id = null;
        $cart->total = $cart->sub_total - 0;
        $cart->save();

        $cartProducts = $cart->cart_products;
        /*check if discount applied on specific product*/
        
        $cartProductsitems = $cart->cart_products;
        foreach ($cartProductsitems as $item) {
            $item->discounted_at = null;
            $item->discount = null;
            $item->save();
        }
        
        if(count($productIds) > 0) {
            $cartProducts = $cart->cart_products->whereIn('product_id', $productIds);
        }
        

        foreach ($cartProducts as $item) {
            $item->discount = $item->quantity * 0;
            $item->discounted_at = null;
            $item->save();
        }
        
        $coupon->discount =  0;
        $coupon->save();
        
        if ($cart->order_id != null) {
            $order = Order::find($cart->order_id);
            $order->coupon_id = null;
            $order->discount = $coupon->discount;
            $order->discount_type = null;
            $order->save();

            $orderItems = $order->order_items()->whereIn('product_id', $cartProducts->pluck('product_id'))->get();
            
            foreach($orderItems as $orderItem){
                $orderItem->discount = $orderItem->quantity * 0;
                $orderItem->discounted_at = null;
                $orderItem->save();
            }
        }

        return [
            'success' => true,
            'id' => $coupon->id,
            'coupon_code' => $coupon->coupon_code,
            'type' => $coupon->type,
            'discount' => $coupon->discount,
            'discounted_amount' => $discounted_amount,
        ];
    }

    /**
     * Covers the following cases.
     * 1. Apply a coupon for the first time. when there was no coupon saved in the cart.
     * 2. Coupon was already saved in the cart, we need to update the cart with new discount , etc.
     * @param string $session_id
     * @param Customer|null $customer
     * @return array|void
     */
    public function applyCoupon($couponCode = '', Cart $cart)
    {
        try{
            $coupon = $this->validateCoupon($couponCode, $cart);

            if($coupon){
                return $this->applyDiscount($coupon, $cart);
            } else {
                return false;
            }
        
        } catch (InvalidCouponException $exc) {
            return Response::error('not-found', __($exc->getMessage()), $exc);
//            return $this->errorResponse(__('Failed to remove cart message.'), [], 400);
        }
    }
    
     public function checkValidateCoupon($couponCode = '', Cart $cart)
    {
        try{
            $coupon = $this->validateCoupon($couponCode, $cart);
        
        } catch (InvalidCouponException $exc) {
           return redirect()->back()->with('error', $exc->getMessage());
//            return Response::error('not-found', __($exc->getMessage()), $exc);
//            return $this->errorResponse($exc->getMessage(), [], 400);
        }
    }
    
    public function errorResponse($errorMessage, $errorData = [], $code = 500)
    {
        $response = [
            'success' => false,
            'message' => $errorMessage,
            'data'    => null
        ];

        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $code);
    }
    /**
     * check coupon expiry
     *
     * @param Coupon $coupon
     * @return array|void
     */
    private function checkCouponExpiry(Coupon $coupon)
    {
        $today_date = now()->toDateTimeString();

        // if coupon code starting date greater than today return
        if (!empty($coupon->start_date) && $coupon->start_date > $today_date) {
            throw new \Exception(__('This coupon is not within valid date.')); // this message needs to be improved
        }

        // if coupon code ending date less than today return
        if (!empty($coupon->end_date) && $coupon->end_date < $today_date) {

            // update coupon status
            $coupon->status = false;
            $coupon->save();

            throw new InvalidCouponException(__('Sorry! this coupon code is expired.'));
        }
    }

    /**
     * check coupon total usage
     *
     * @param Coupon $coupon
     * @param Customer|null $customer
     * @return array|void
     */
    private function checkCouponTotalUsage(Coupon $coupon, ?Customer $customer)
    {
        // check coupon usage limit
        if (!$coupon->isLimited()) {
            return;
        }
        
        // check remaining limit
        $couponCount = Order::whereCouponId($coupon->id)->where('status', '<>', Order::UNIITIATED_ID)->count();

        if ($couponCount >= $coupon->total_limit) {
            throw new InvalidCouponException(__('No more usage limit remaining for this coupon code.'));
        }
       
        // check customer remaining limit
        if ($coupon->per_user_limit == null || $customer == null) {
            return;
        }
        
        $couponUsersCount = Order::whereCouponId($coupon->id)->where('status', '<>', Order::UNIITIATED_ID)->whereCustomerId($customer->id)->count();
          
        if ($couponUsersCount >= $coupon->per_user_limit) {
            throw new InvalidCouponException(__('You have reached your limit for this coupon code.'));
        }
    }

    /**
     * get coupon discount
     *
     * @param Coupon $coupon
     * @param Cart $cart
     * @return array
     */
    private function applyDiscount(Coupon $coupon, Cart $cart): array
    {
        
        // if type 'all products'
        if ($coupon->applies_to == Coupon::COUPON_APPLY_TO_ALL_PRODUCTS) {
            $discounted_amount = $this->getDiscountedAmount($cart->sub_total, $coupon->discount, $coupon->type);
            return $this->getSuccessResponse($coupon, $cart, $discounted_amount);
        }

        // if type 'specific products'
        if ($coupon->applies_to == Coupon::COUPON_APPLY_TO_SPECIFIC_PRODUCTS) {
            return $this->getDiscountForSpecificProducts($coupon, $cart);
        }

        // if type 'specific categories'
        if ($coupon->applies_to == Coupon::COUPON_APPLY_TO_SPECIFIC_CATEGORIES) {
            return $this->getDiscountForSpecificCategories($coupon, $cart);
        }

        // if type 'sub total'
        if ($coupon->applies_to == Coupon::COUPON_APPLY_TO_SUBTOTAL) {
            return $this->getDiscountForSubTotal($coupon, $cart);
        }

        // if type 'shipping'
        if ($coupon->applies_to == Coupon::COUPON_APPLY_TO_SHIPPING) {
            return $this->getDiscountForShipping($coupon, $cart);
        }
        
        // if type 'store'
        if ($coupon->applies_to == Coupon::COUPON_APPLY_TO_STORE) {
            return $this->getDiscountForShipping($coupon, $cart);
        }
        
        // if type 'buy & get free'
        if ($coupon->applies_to == Coupon::COUPON_APPLY_TO_BUY_GET) {
            return $this->getDiscountForBuyGetFree($coupon, $cart);
        }
    }

    /**
     * get discount for specific products
     *
     * @param Coupon $coupon
     * @param Cart $cart
     * @return array
     */
    private function getDiscountForSpecificProducts(Coupon $coupon, Cart $cart): array
    {
        $couponProducts = $coupon->products->pluck('id');
        $discounted_amount = 0;
        $productIds = [];

        foreach ($cart->cart_products as $item) {
            if ($couponProducts->contains($item->product_id)) {
                array_push($productIds, $item->product_id);
                if ($coupon->type === 'fixed') {
                    $discounted_amount = (float)$coupon->discount;
                    break;
                }

                $item_total = $item->unit_price * $item->quantity;
                $discounted_amount += $this->getDiscountedAmount($item_total, $coupon->discount, $coupon->type);
            }
        }

        // if discounted amount is equal to 0
        if ($discounted_amount == 0) {
            throw new \Exception(__('You are not eligible for this coupon code.'));
        }

        return $this->getSuccessResponse($coupon, $cart, $discounted_amount, $productIds);
    }

    /**
     * get discount for specific categories
     *
     * @param Coupon $coupon
     * @param Cart $cart
     * @return array
     */
    private function getDiscountForSpecificCategories(Coupon $coupon, Cart $cart): array
    {
        $couponCategories = $coupon->categories->pluck('id');
        $discounted_amount = 0;
        $productIds = [];
        $isCountDiscount = true;

        foreach ($cart->cart_products as $key =>  $item) {

            $product_categories = $item->product->categories->pluck('id');
            $check_category = $couponCategories->contains(function ($category_id) use ($product_categories) {
                return $product_categories->contains($category_id);
            });
           
            if ($check_category) {
                $productIds[] = $item->product_id;

                if($isCountDiscount) {
                    if ($coupon->type === 'fixed') {
                        $discounted_amount = (float)$coupon->discount;
                        $isCountDiscount = false;
                        continue;
//                        break;
                    }

                    $item_total = $item->unit_price * $item->quantity;
                    $discounted_amount += $this->getDiscountedAmount($item_total, $coupon->discount, $coupon->type);
                }
            }
        }

        if ($discounted_amount == 0) {
            throw new \Exception(__('You are not eligible for this coupon code.'));
        }

        return $this->getSuccessResponse($coupon, $cart, $discounted_amount, $productIds);
    }

    /**
     * get discount for sub total
     *
     * @param Coupon $coupon
     * @param Cart $cart
     * @return array
     */
    private function getDiscountForSubTotal(Coupon $coupon, Cart $cart): array
    {
        if ($cart->sub_total < $coupon->sub_total) {
            throw new \Exception(__('You are not eligible for this coupon code.'));
        }

        $discounted_amount = $this->getDiscountedAmount($cart->sub_total, $coupon->discount, $coupon->type);
        return $this->getSuccessResponse($coupon, $cart, $discounted_amount);
    }

    /**
     * get discount for shipping
     *
     * @param Coupon $coupon
     * @param Cart $cart
     * @return array
     */
    private function getDiscountForShipping(Coupon $coupon, Cart $cart): array
    {
        $total_devliery_fee = $cart->cart_products->reduce(function ($total, $item) {
            return $total + ($item->quantity * $item->delivery_fee);
        }, 0);

        $discounted_amount = $this->getDiscountedAmount($total_devliery_fee, $coupon->discount, $coupon->type);
        
        return $this->getSuccessResponse($coupon, $cart, $discounted_amount);
    }
    
     /**
     * get discount for specific categories
     *
     * @param Coupon $coupon
     * @param Cart $cart
     * @return array
     */
     private function getDiscountForBuyGetFree(Coupon $coupon, Cart $cart): array {
        $couponStores = $coupon->store_id;
        $get_limit = $coupon->get_limit;
        $buy_limit = $coupon->buy_limit;
        $discounted_amount = 0;
        $productIds = [];
        $isCountDiscount = true;
        $products = [];
        $cartProductId = '';
        $cartitemId = '';
        $productQty = 0;
        
        foreach ($cart->cart_products as $key => $item) {

            $product_store = $item->product->store_id;
            if ($couponStores == $product_store) {
                $productIds[] = $item->product_id;
                $productUnitPrice[$item->product_id] = $item->unit_price;
                $products[] = $item;
                $productQty = $productQty + $item->quantity;
            }
        }

        if ($productQty > $buy_limit) {
            if ($isCountDiscount) {
                if ($coupon->type === 'get_free') {

                    $product = collect($products);
                    $product = $product->where('unit_price', $product->min('unit_price'))->first();
                    $cartProductId = $product->product_id;
                    $cartitemId = $product->id;
                    $discounted_amount = (float) $product->unit_price;
                    $isCountDiscount = false;
                }
            }
        }

        if ($discounted_amount == 0) {
            return $this->getErrorResponse($coupon, $cart, $discounted_amount, $productIds, $cartProductId, $cartitemId);
        }

        return $this->getSuccessResponse($coupon, $cart, $discounted_amount, $productIds, $cartProductId, $cartitemId);
    }

}
