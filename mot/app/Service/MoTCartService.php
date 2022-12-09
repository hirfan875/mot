<?php

namespace App\Service;

use App\Exceptions\InvalidProductException;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
use App\Models\StoreOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Helpers\UtilityHelpers;
use App\Models\Coupon;
use App\Service\CouponDiscountService;
use Monolog\Logger;
use App\Models\Currency;

class MoTCartService
{

    /** @var Cart */
    protected $cart;
    /** @var Logger */
    protected $logger;

    public function __construct($sessionId = null)
    {
        $this->logger = getLogger('MoTCartService');
        if (empty($sessionId)) {
//            $this->logger->debug('No session Supplied for MoTCartService ' );
            $sessionId = $this->getCartSessionId();
        }
//        $this->logger->debug('Getting Cart In MoTCartService Constructor ' );
        $this->cart = $this->getCart($sessionId);
    }

    protected function getCartSessionId()
    {
        $cartSessionId = UtilityHelpers::setCartSessionId();
        return $cartSessionId;
    }

    public function thisCart()
    {
       return $this->cart;
    }

    public function getCart($sessionId)
    {
        $cart = Cart::whereSessionId($sessionId)->where('status', '!=', Cart::TERMINATED_ID)->first();
        if ($cart) {
            $this->logger->debug('Got Cart From DB using session '  . $sessionId);
            return $cart;
        }

        $cart = new Cart([
            'session_id' => $sessionId,
            // TODO ensure we set the currency value base on what
            // currency is shown to the user
            'currency_id' => 1,
            'status'    => Cart::OPEN_ID,
        ]);
        $cart->save();


        $this->logger->debug('Created new Cart with session '  . $sessionId  , [$cart->id, request()->toArray()]);
        return $cart;
    }

    /**
     * @param Product $product
     * @param $quantity
     * @return CartProduct
     * @throws \Exception
     */
    public function addItem(Product $product, $quantity)
    {
        if ($product->isVariable() && $product->hasNoParent()){
            throw new InvalidProductException('You cant add this Product');
        }
        if ($product->stock < $quantity) {
            throw new InvalidProductException('Not enough stock available.');
        }
        $cartProduct = new CartProduct([
            'cart_id' => $this->cart->id,
            'product_id' => $product->id,
            'name' => $product->title,
            'delivery_fee' => $product->delivery_fee,
            'quantity' => $quantity,
            'unit_price' => $product->promo_price ? $product->promo_price : $product->price,
            // TODO ensure we set the currency value base on what
            // currency is shown to the user
            'currency_id' => 1,
        ]);
        $cartProduct->save();

        return $cartProduct;
    }

    public function updateItem(Product $product, $newQuantity,$status=null)
    {
        // find product
        $cartProduct = $this->cart->cart_products()->where('product_id' ,$product->id)->first();
        // update new quantity if found

        if ($cartProduct) {


            if ($newQuantity == 0) {
                $cartProduct->delete();
                return $this;
            }
            if ($status == 'add') {
               
                if ($product->stock < ( $cartProduct->quantity + 1 )) {
                    throw new InvalidProductException('OUT OF STOCK');
                }
                $cartProduct->quantity = $cartProduct->quantity + 1;
            } else if ($status == 'remove') {
                if($cartProduct->quantity > 1) {
                    $cartProduct->quantity = $cartProduct->quantity - 1;
                }
            } else {
//                dd($product->stock,$cartProduct->quantity,$newQuantity);
                if ($product->stock < ( $cartProduct->quantity + $newQuantity )) {
                    throw new InvalidProductException('OUT OF STOCK');
                }
                $cartProduct->quantity = $cartProduct->quantity + $newQuantity;
            }
            $cartProduct->save();

//            $this->updateCartCustomer();
            $this->updateCart(); //update cart after delete item
            return $this;
        }
        if ($newQuantity == 0){
            return $this;
        }
        // add product if not found and quantity is > 0
        $this->addItem($product, $newQuantity);
        $this->removeDeliveryFee();
        $this->updateCart(); //update cart after delete item

        return $this;

    }

    public function updateItemDeliveryRate(Product $product, $deliveryRate)
    {
        // find product
        $cartProduct = $this->cart->cart_products()->where('product_id' ,$product->id)->first();
        // update new deliveryRate if found
        if ($cartProduct) {

            $cartProduct->delivery_rate = $deliveryRate;
            $cartProduct->save();
            $this->updateCartTotal($deliveryRate);
            return $this;
        }

        return $this;
    }

    public function updateCartDeliveryFee($deliveryRate)
    {
        $this->cart->delivery_fee = $deliveryRate;
        $this->cart->save();
        $this->updateCartTotal($deliveryRate);
        return $this;
    }

    public function removeItem(CartProduct $cart_product)
    {
        $this->logger->debug('Removing Cart Product' , $cart_product->toArray());
        /** @var CartProduct $cartProduct */
        $cartProduct = $this->cart->cart_products()
            ->where('id', $cart_product->id)
            ->first();
        if (!$cartProduct) {
            $this->logger->debug('Cart Product is not part of the current cart', [
                'cart_id' =>  $this->cart->id,
                'session_id' =>  $this->cart->session_id,
                'cart_product_id'=>$cart_product->id
            ]);
            return $this;
        }
        $cartProduct->delete();
        $this->removeDeliveryFee();
        $this->updateCart(); //update cart after delete item
        return $this;
    }

    public function getContent()
    {
        return $this->cart->cart_products;
    }

    public function getSubTotal()
    {
        $total = 0;
        foreach ($this->cart->cart_products as $product) {
            $this->logger->debug('Accounting for cart product ' , $product->toArray());
            $total += abs($product->unit_price * $product->quantity);
        }
        $this->logger->debug('Cart Sub Total is ' , [$total]);
        return $total;
    }

    public function getDeliveryFee()
    {
        return $this->cart->delivery_fee;
    }

    public function getTotal()
    {
        $this->logger->debug('Cart Sub Total is ' , [
            'sub_total'=> $this->getSubTotal(),
            'delivery_fee'=>$this->getDeliveryFee(),
            'discount'=> $this->getDiscountedAmount()
        ]);

        return $this->getSubTotal() + $this->getDeliveryFee() - $this->getDiscountedAmount();
    }

    /**
     * @param Product $product
     * @param $newQuantity
     * @return CartProduct
     */
    protected function addProductToCart(Product $product, $newQuantity): CartProduct
    {
        $cartProduct = new CartProduct([
            'cart_id' => $this->cart->id,
            'product_id' => $product->id,
            'name' => $product->title,
            'delivery_fee' => $product->delivery_fee,
            'quantity' => $newQuantity,
            'unit_price' => $product->promo_price,
            // TODO ensure we set the currency value base on what
            // currency is shown to the user
            'currency_id' => 1,
        ]);
        return $cartProduct;
    }

    public function TotalQuantity(){
        return $this->cart->cart_products->sum('quantity');
    }

    /**
     * @return mixed
     */
    public function getCartProduct(){
        return $this->cart->cart_products;
    }

    public function getCartListItems(){

        $cart_products = CartProduct::with(['product','product.store','product.gallery','product.variation_attributes','product.parent', 'cart','product.product_translates'])->whereHas('cart', function($query){
                    $query->where('session_id', $this->getCartSessionId())->where('status', '!=', Cart::TERMINATED_ID);
                })->whereHas('product')->get();

        return $cart_products;
    }

    public function getCartListItemsApis($session_id){

        $cart_products = CartProduct::with(['product','product.store','product.gallery','product.variation_attributes','product.parent', 'cart','product.product_translates'])->whereHas('cart', function($query) use ($session_id){
                    $query->where('session_id', $session_id)->where('status', '!=', Cart::TERMINATED_ID);
                })->whereHas('product')->get();

        return $cart_products;
    }

    public function updateCart()
    {
        $cart = $this->getCart($this->getCartSessionId());
        $cart->sub_total = $this->getSubTotal();
        $cart->total = $this->getTotal();
        $this->updateCartCustomer();
        $cart->save();

        return $this;
    }

    public function updateCartCustomer()
    {
        $customer_id = null;
        $customer = Auth::guard('customer')->user();
        if ($customer != null) {
            $customer_id = $customer->id;
        }
        $cart = $this->getCart($this->getCartSessionId());
        $cart->customer_id = $customer_id;
        $cart->save();

        return $this;
    }

    public function getAbandonedCart() {
        $customer_id = null;
        $customer = Auth::guard('customer')->user();
        if ($customer != null) {
            $customer_id = $customer->id;
        }

        $oldCart = Cart::where('customer_id', $customer_id)->where('status', '!=', Cart::TERMINATED_ID)->with('cart_products')->has('cart_products')->latest()->first();
        $cart = $this->getCart($this->getCartSessionId());
        
        if ($oldCart != null) {
            if ($oldCart->session_id != $cart->session_id) {
                $oldCart->update(['status' => Cart::TERMINATED_ID]);
                foreach ($oldCart->cart_products as $old) {
                    $i = true;
                    foreach ($cart->cart_products as $newProduct) {
                        if ($newProduct->product_id != $old->product_id) {
                            $cProduct = CartProduct::where('product_id', $old->product_id)->where('cart_id', $this->cart->id)->first();
                            if ($cProduct == null) {
                                if ($i) {
                                    $cartProduct = new CartProduct([
                                        'cart_id' => $this->cart->id,
                                        'product_id' => $old->product_id,
                                        'name' => $old->title,
                                        'delivery_fee' => $old->delivery_fee,
                                        'quantity' => $old->quantity,
                                        'unit_price' => $old->unit_price,
                                        'currency_id' => 1,
                                    ]);
                                    $cartProduct->save();
                                }
                                $i = false;
                            }
                        }
                    }
                }
            }
        }

        $cart->customer_id = $customer_id;
        $cart = $this->getCart($this->getCartSessionId());
        $cart->sub_total = $this->getSubTotal();
        $cart->total = $this->getTotal();
        $this->updateCartCustomer();
        $cart->save();
        
       
        return $this;
    }

    public function removeDeliveryFee()
    {
        $this->cart->delivery_fee = 0;
        $this->cart->save();

        return $this;
    }

    public function updateCartTotal($deliveryRate)
    {
        $this->cart->total = $this->getSubTotal() + $deliveryRate - $this->getDiscountedAmount();
        $this->cart->save();
        return $this;
    }

    public function getCartArray(){
        return $this->cart->toArray();
    }

    public function updateStatus($status)
    {
        $cart = $this->getCart($this->getCartSessionId());
        $cart->status = $status;
        $cart->save();
        return $cart;
    }

    public function terminateCart()
    {
        $this->cart->status = Cart::TERMINATED_ID;
        $this->cart->save();
        return $this->cart;
    }

    public function getDiscountedAmount()
    {
        $discountedAmount = 0;
        $coupon = $this->getCart($this->getCartSessionId())->coupon;
        $items = $this->getCart($this->getCartSessionId())->cart_products->where('discounted_at','Free')->first();


        if($coupon != null){
            $coupon_discount =   $coupon->discount;
        if ($coupon->type === 'get_free') {
            if($items){
                $coupon_discount = $items->unit_price;
            } else {
                $coupon_discount = 0;
            }
        }
            $discountService = new CouponDiscountService();
            $discountedAmount = $discountService->getDiscountedAmount($this->getSubTotal(), $coupon_discount, $coupon->type);
        }

        return $discountedAmount;
    }

    public function getCartMessages()
    {
        $cartProducts = $this->cart->cart_products()->get();
        $cartMessages = [];

        foreach($cartProducts as $cartProduct)
        {
            if($cartProduct->message != null)
            {
                array_push($cartMessages, $cartProduct->message);
            }
        }

        return $cartMessages;
    }

    public function removeCartMessage()
    {
        $cartProducts = $this->cart->cart_products()->get();

        foreach($cartProducts as $cartProduct)
        {
            $cartProduct->message = null;
            $cartProduct->save();
        }
        return $this;
    }

    public function updateCartForexRate($getCurrency)
    {
        $tryCurrency = Currency::where('code', 'TRY')->first();
        $this->cart->currency_id =  $getCurrency->id;
        $this->cart->forex_update_datetime = Carbon::now()->toDateTimeString();
        $this->cart->forex_rate = number_format($getCurrency->base_rate,3);
        $this->cart->base_forex_rate = number_format($tryCurrency->base_rate,3);
        $this->cart->save();
        return $this;
    }


    public function addOrderCart($order_id)
    {
        $this->cart->order_id = $order_id;
        $this->cart->save();
        return $this->cart;

    }

    public function emptyCart()
    {
        $cartProducts = $this->cart->cart_products();
        if ($cartProducts->count() > 0) {
            $cartProducts->delete();
        }
        $this->removeDeliveryFee();
        $this->updateCart();
        return $this;
    }

}
