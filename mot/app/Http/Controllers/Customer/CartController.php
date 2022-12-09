<?php

namespace App\Http\Controllers\Customer;

use App\Exceptions\InvalidProductException;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Service\MoTCartService;
use App\Exceptions\InvalidCouponException;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Service\CouponDiscountService;
use App\Service\CustomerAddressService;
use App\Helpers\UtilityHelpers;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Auth;
use Session;
use App\Extensions\Response;
use App\Service\OrderService;


class CartController extends Controller
{
    public function getCartSessionId() {
//        $logger = getLogger('MoTCartService');
        $cartSessionId = UtilityHelpers::getCartSessionId();
//        $logger->debug('Cart Session Id is ' . $cartSessionId);
        return $cartSessionId;
    }

    public function index()
    {
        try {
        $cart = $this->cartService();
    	$cartProducts = $this->cartService()->getCartListItems();
        $cartMessages = $this->cartService()->getCartMessages();
        $countries = Country::whereStatus(true)->orderBy('is_default', 'desc')->get();
        $states = State::whereStatus(true)->get();
        $cities = City::whereStatus(true)->get();

        $cart->updateCartForexRate(getCurrency());
        $orderService = new OrderService();
        $orderService->updateOrderForexRate(getCurrency());
        $couponCode ='';

        $cartSessionId = UtilityHelpers::getCartSessionId();
        $carts = Cart::where('session_id', $cartSessionId)->first();
        $couponService = new CouponDiscountService();
        $discount = $couponService->checkValidateCoupon($couponCode, $carts);
        couponDiscount();
        $customer = null;
        $customerAddresses = null;

        Session::forget('orderId');



        if(Auth::guard('customer')->user() != null) {
            $customer = Auth::guard('customer')->user();
            $customerAddressService = new CustomerAddressService();
            $customerAddresses = $customerAddressService->getAllAddresses($customer->id);
        }
        } catch (InvalidProductException $exc) {
            return $this->errorResponse(__('Failed to add item in cart.'), [], 400);
        }
    	return view('web.cart.index', ['states' => $states, 'cities' => $cities, 'cart' => $cart, 'cart_products' => $cartProducts, 'customer' => $customer, 'customer_addresses' => $customerAddresses, 'cart_messages' => $cartMessages, 'countries' => $countries]);
    }

    public function addToCart(Request $request)
    {
        try {
            $product_id = $request->product_id;
            $quantity = $request->quantity;
            $status = $request->status;

            $product = Product::findOrFail($product_id);

            $addedToCart = $this->cartService()->updateItem($product, $quantity,$status);
            couponDiscount();
            $cartItemsQuantity = $this->cartService()->TotalQuantity();
            $cart = $this->cartService()->getCartProduct();
            $cartItem = $this->cartService()->getCartProduct()->where('product_id' ,$product_id)->first();

            $data['cartItemsQuantity'] = $cartItemsQuantity;
            $data['cart_data'] = $cart;
            $data['unit_price'] = currency_format($cartItem->unit_price);
            $data['sub_total'] = currency_format($cartItem->unit_price * $cartItem->quantity);

            if ($addedToCart) {
                return $this->successResponse(__('Item has been added to cart successfully'), $data);
            }
        } catch (InvalidProductException $exc) {
            return $this->errorResponse(__('OUT OF STOCK'), [__($exc->getMessage())], 400);
        }
        return $this->errorResponse(__('Failed to add item in cart.'), []);
    }

    public function removeItem(Request $request)
    {
    	$item_id = $request->id;
    	$cart_product = CartProduct::find($item_id);
        if(!$cart_product){
            return $this->errorResponse(__('The Item you tried to remove is not in the cart.'), []);
        }

        $removedItem 	   = $this->cartService()->removeItem($cart_product);
        couponDiscount();
    	$cartItemsQuantity = $this->cartService()->TotalQuantity();
        $cart = $this->cartService()->getCartProduct();

        $data['cartItemsQuantity'] = $cartItemsQuantity;
        $data['cart_data'] = $cart;

        if($removedItem){
            return $this->successResponse(__('Item has been removed from cart.'), $data);
        }
        return $this->errorResponse(__('Failed to remove item from cart.'), []);
    }

    public function cartService()
    {
        $cartService = new MoTCartService($this->getCartSessionId());
        return $cartService;
    }

    /**
     * success response method.
     *
     * @param $message
     * @param $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($message, $result)
    {
        $response = [
            'success'   => true,
            'message'   => $message,
            'data'      => $result
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @param $errorMessage
     * @param array $errorData
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
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

    public function applyCoupon(Request $request)
    {

        $couponCode = $request->coupon;
        if (isset($couponCode) && $couponCode != null) {
            $coupon = Coupon::whereStatus(true)->whereCouponCode(strtoupper(trim($couponCode)))->first();
            if (!$coupon) {
                throw new \Exception(__('Coupon code is not valid.'));
            }
        }
        $cartSessionId = UtilityHelpers::getCartSessionId();
        $cart = Cart::where('session_id', $cartSessionId)->first();

        $couponService = new CouponDiscountService();
        $discount = $couponService->applyCoupon($couponCode, $cart);
        $cart = $this->cartService();
        $data = [
            'discount' => $discount,
            'subTotal' => currency_format($cart->getSubTotal()),
            'discountedAmount' => currency_format($cart->getDiscountedAmount()),
            'deliveryFee' => ($cart->getDeliveryFee() > 0 ) ? currency_format($cart->getDeliveryFee()) : 'Free Shipping',
            'total' => currency_format($cart->getTotal()),
        ];
        return Response::success(null, $data, $request);

    }


    public function removeCartMessage()
    {
        try {
            $cart = $this->cartService();
            $removed = $cart->removeCartMessage();

            if($removed) {
                return $this->successResponse(__('Cart Message has been removed successfully'), []);
            }
        } catch (InvalidProductException $exc) {
            return $this->errorResponse(__('Failed to remove cart message.'), [], 400);
        }
        return $this->errorResponse(__('Failed to remove cart message.'), []);
    }

    public function emptyCart()
    {
        try {
            $cart = $this->cartService();
            $removed = $cart->emptyCart();

            if ($removed) {
                return $this->successResponse(__('Cart has been empty successfully'), []);
            }
        } catch (InvalidProductException $exc) {
            return $this->errorResponse(__('Failed to remove cart.'), [], 400);
        }
        return $this->errorResponse(__('Failed to remove cart.'), []);
    }
}
