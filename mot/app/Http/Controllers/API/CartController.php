<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CartProductsResource;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use App\Exceptions\InvalidProductException;
use App\Service\ApiCartService;
use App\Exceptions\InvalidCouponException;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Service\CouponDiscountService;
use App\Service\CustomerAddressService;
use App\Helpers\UtilityHelpers;
use Auth;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Service\OrderService;

class CartController extends BaseController
{
    public function getCartSessionId()
    {
        $cartSessionId = UtilityHelpers::setCartSessionId();
        return $cartSessionId;
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $cartService = new ApiCartService($request->session_id);
        $cart = $cartService->getCart();
        $cartItemsQuantity = $cartService->TotalQuantity();
        $cartProducts = $cartService->getCartListItemsApis($request->session_id);
        $cartMessages = $cartService->getCartMessages();
        $currency_id = isset($request->currency_id) ? $request->currency_id : '';
        $cartService->updateCartForexRate(getCurrencyById($currency_id));
        $orderService = new OrderService();
        $orderService->updateOrderForexRate(getCurrencyById($currency_id));

        $customer = null;
        $customerAddresses = null;

        Session::forget('orderId');

        if (Auth::guard('customer')->user() != null) {
            $customer = Auth::guard('customer')->user();
            $customerAddressService = new CustomerAddressService();
            $customerAddresses = $customerAddressService->getAllAddresses($customer->id);
        }

        $data['cart'] = $cart;
        $data['products'] = CartProductsResource::collection($cartProducts);
        $data['cartItemsQuantity'] = $cartItemsQuantity;
        $data['discount'] = (double)$cartService->getDiscountedAmount();
        $data['customer'] = $customer;
        $data['customer_addresses'] = $customerAddresses;
        $data['cart_messages'] = $cartMessages;

        return $this->sendResponse($data, __('Item has been added to cart successfully'));
    }

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        try {

            $product_id = $request->product_id;
            $quantity = $request->quantity;
            $status = $request->status;
            $product = Product::findOrFail($product_id);

            if (isset($request->session_id) && $request->session_id != '') {
                $cartService = new ApiCartService($request->session_id);
            } else {
                $cartService = $this->cartService();
            }

            $addedToCart = $cartService->updateItem($product, $quantity, $status);
            $cartItemsQuantity = $cartService->TotalQuantity();
            $cartProducts = $cartService->getCartProduct();

            $data['cart'] = $cartService->getCart();
            $data['cartItemsQuantity'] = $cartItemsQuantity;
            $data['products'] = CartProductsResource::collection($cartProducts);

            if ($addedToCart) {
                return $this->sendResponse($data, __('Item has been added to cart successfully'));
            }
        } catch (InvalidProductException $exc) {
            return $this->sendError(__('Failed to add item in cart.'), __($exc->getMessage()));
        }
        return $this->sendError(__('Failed to add item in cart.'));
    }

    public function removeItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'session_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $item_id = $request->id;
        $cart_product = CartProduct::find($item_id);
        if (!$cart_product) {
            return $this->errorResponse(__('The item you tried to remove is not in the cart.'), []);
        }

        $cartService = new ApiCartService($request->session_id);

        $removedItem = $cartService->removeItem($cart_product);
        $cartItemsQuantity = $cartService->TotalQuantity();
        $cart = $cartService->getCartProduct();

        $data['cart'] = $cartService->getCart();
        $data['products'] = CartProductsResource::collection($cart);
        $data['cartItemsQuantity'] = $cartItemsQuantity;

        if ($removedItem) {
            return $this->successResponse(__('Item has been removed from cart.'), $data);
        }
        return $this->errorResponse(__('Failed to remove item from cart.'), []);
    }

    public function cartService()
    {
        $cartService = new ApiCartService($this->getCartSessionId());
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
            'success' => true,
            'message' => $message,
            'data' => $result
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
            'data' => null
        ];

        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $code);
    }

    public function applyCoupon(Request $request)
    {
        try {
            $couponCode = $request->coupon;
            $cartSessionId = $request->session_id;
            $cart = Cart::where('session_id', $cartSessionId)->first();

            $couponService = new CouponDiscountService();
            $discount = $couponService->applyCoupon($couponCode, $cart);
            $cart = $this->cartService();
            $data = [
                'discount' => $discount,
                'subTotal' => currency_format($cart->getSubTotal()),
                'discountedAmount' => currency_format($cart->getDiscountedAmount()),
                'deliveryFee' => currency_format($cart->getDeliveryFee()),
                'total' => currency_format($cart->getTotal()),
                'request' => $request
            ];

            return $this->successResponse(__('Coupon has been applied successfully.'), $data);
        } catch (InvalidCouponException $exc) {
            return $this->sendError(__('Failed to apply coupon.'), __($exc->getMessage()));
        }
    }

    public function topCartItems(Request $request)
    {
        try {

            $cartCount = 0;
            $topCartItems = '';
            $cartSubtotal = 0;

            if ($request->session_id) {
                $cartService = new ApiCartService($request->session_id);
                $cartCount = $cartService->TotalQuantity();
                $topCartItems = $cartService->getCartListItemsApis($request->session_id);
                $cartSubtotal = $cartService->getSubTotal();
            }

            $data = [
                'cartCount' => $cartCount,
                'topCartItems' => $topCartItems,
                'cartSubtotal' => $cartSubtotal
            ];
            return $this->successResponse(__('top Cart Items.'), $data);
        } catch (InvalidCouponException $exc) {
            return $this->sendError(__('Failed to top Cart Items.'), __($exc->getMessage()));
        }
    }

    public function removeCartMessage()
    {
        try {
            $cart = $this->cartService();
            $removed = $cart->removeCartMessage();

            if ($removed) {
                return $this->successResponse(__('Cart Message has been removed successfully'), []);
            }
        } catch (InvalidProductException $exc) {
            return $this->errorResponse(__('Failed to remove cart message.'), [], 400);
        }
        return $this->errorResponse(__('Failed to remove cart message.'), []);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function clearCart(Request $request)
    {
        try {
            if (isset($request->session_id) && $request->session_id != '') {
                $cartService = new ApiCartService($request->session_id);
            } else {
                $cartService = $this->cartService();
            }
            $removed = $cartService->emptyCart();
            if ($removed) {
                return $this->successResponse(__('Cart has been cleared successfully'), []);
            }
        } catch (InvalidProductException $exc) {
            return $this->errorResponse(__('Failed to clear cart.'), [], 400);
        }
    }
}
