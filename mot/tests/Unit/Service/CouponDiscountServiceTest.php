<?php

namespace Tests\Unit\Service;


use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreOrder;
use App\Service\CouponDiscountService;
use App\Service\MoTCartService;
use App\Service\OrderService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CouponDiscountServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Coupon is not in DB
     */
    public function testInvalidCoupon()
    {
        $this->expectException(\Exception::class);
        /** @var Coupon $coupon */
        $coupon = Coupon::factory()->create([
            'coupon_code'=>'COUPON-123',
            'status' => false
        ]);
        $cart = Cart::factory()->create();

        $couponDiscountService = new CouponDiscountService();
        $couponDiscountService->applyCoupon($coupon->coupon_code, $cart);
    }

    /**
     * Coupon is expired
     */
    public function testDateExpiredCoupon()
    {
        $this->expectException(\Exception::class);
        /** @var Coupon $coupon */
        $coupon = Coupon::factory()->create([
            'coupon_code'=>'COUPON-123',
            'end_date' => Carbon::yesterday()]);
        $cart = Cart::factory()->create();

        $couponDiscountService = new CouponDiscountService();
        $couponDiscountService->applyCoupon($coupon->coupon_code, $cart);
    }

    /**
     * Coupon started date is in future
     */
    public function testDateNotStartedCoupon()
    {
        $this->expectException(\Exception::class);
        /** @var Coupon $coupon */
        $coupon = Coupon::factory()->create([
            'coupon_code' => 'COUPON-123',
            'start_date' => Carbon::tomorrow()]);
        $cart = Cart::factory()->create();

        $couponDiscountService = new CouponDiscountService();
        $couponDiscountService->applyCoupon($coupon->coupon_code, $cart);
    }

    /**
     * Coupon is usage total limit is all used up
     */
    public function testTotalLimitCoupon()
    {
        $this->expectException(\Exception::class);
        /** @var Coupon $coupon */
        $coupon = Coupon::factory()->create([
            'coupon_code' => 'COUPON-123',
            'usage' => Coupon::COUPON_USAGE_LIMITED,
            'total_limit' => 1
        ]);
        $cart = Cart::factory()->create();

        // Create usage
        Order::factory()->create(['status' => Order::CONFIRMED_ID, 'coupon_id' => $coupon->id]);

        $couponDiscountService = new CouponDiscountService();
        $couponDiscountService->applyCoupon($coupon->coupon_code, $cart);
    }

    /**
     * Coupon is usage total limit is all used up
     */
    public function testCustomerLimitCoupon()
    {
        $this->expectException(\Exception::class);
        $customer = Customer::factory()->create();
        /** @var Coupon $coupon */
        $coupon = Coupon::factory()->create([
            'coupon_code' => 'COUPON-123',
            'usage' => Coupon::COUPON_USAGE_LIMITED,
            'total_limit' => 300,
            'per_user_limit' => 1

        ]);
        $cart = Cart::factory()->create(['customer_id' => $customer->id]);

        // Create usage
        Order::factory()->create(['status' => Order::CONFIRMED_ID, 'coupon_id' => $coupon->id, 'customer_id' => $customer->id]);

        $couponDiscountService = new CouponDiscountService();
        $couponDiscountService->applyCoupon($coupon->coupon_code, $cart);
    }

    /**
     * customer has previously used all limit for this coupon
     */
    public function testSubTotalLimitCoupon()
    {
        $this->expectException(\Exception::class);
        $customer = Customer::factory()->create();
        /** @var Coupon $coupon */
        $coupon = Coupon::factory()->create([
            'coupon_code' => 'COUPON-123',
            'applies_to' => Coupon::COUPON_APPLY_TO_SUBTOTAL,
            'sub_total' => 2
        ]);
        $cart = Cart::factory()->create(['customer_id' => $customer->id]);
        $cartProduct = CartProduct::factory()->create([
            'cart_id' => $cart->id,
            'unit_price' => 1,
            'quantity' => 1,
            'delivery_fee' => 0
        ]);
        // Create usage
        Order::factory()->create(['status' => Order::CONFIRMED_ID, 'coupon_id' => $coupon->id, 'customer_id' => $customer->id]);

        $couponDiscountService = new CouponDiscountService();
        $couponDiscountService->applyCoupon($coupon->coupon_code, $cart);

    }

    /**
     * This coupon is limited for one seller, which you have no product
     */
    public function testSellerLimitCoupon()
    {
        $this->expectException(\Exception::class);
        $customer = Customer::factory()->create();
        $store = Store::factory()->create();
        /** @var Coupon $coupon */
        $coupon = Coupon::factory()->create([
            'coupon_code' => 'COUPON-123',
            'store_id' => $store->id,
            'sub_total' => 2]);
        $cart = Cart::factory()->create(['customer_id' => $customer->id]);

        $store = Store::factory()->create(); // a differnt store product is in cart
        $product = Product::factory()->create(['store_id' => $store->id]);
        $cartProduct = CartProduct::factory()->create([
            'product_id' => $product->id,
            'cart_id' => $cart->id,
        ]);

        $couponDiscountService = new CouponDiscountService();
        $couponDiscountService->applyCoupon($coupon->coupon_code, $cart);

    }

    /**
    // TODO for tahir There should be 5x2 success cases [or 6]
     * const COUPON_APPLY_TO_ALL_PRODUCTS = 1;
     * const COUPON_APPLY_TO_SPECIFIC_PRODUCTS = 2;
     * const COUPON_APPLY_TO_SPECIFIC_CATEGORIES = 3;
     * const COUPON_APPLY_TO_SUBTOTAL = 4;
     * const COUPON_APPLY_TO_SHIPPING = 5;
     *
     * Times 2 for
     * All seller
     * Specific seller
     *
     */
    public function testSuccessCoupon()
    {
        $customer = Customer::factory()->create();
        $store = Store::factory()->create();
        /** @var Coupon $coupon */
        $coupon = Coupon::factory()->create([
            'coupon_code' => 'COUPON-123',
            'type' => 'fixed',
            'discount' => 2,
            'applies_to' => Coupon::COUPON_APPLY_TO_SUBTOTAL,
            'store_id' => $store->id,
            'sub_total' => 2,
            'start_date' => Carbon::yesterday(),
            'end_date' => Carbon::tomorrow(),
        ]);
        $cart = Cart::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);
        $cartProduct = CartProduct::factory()->create([
            'product_id' => $product->id,
            'cart_id' => $cart->id,
            'unit_price' => 1,
            'quantity' => 10,
            'delivery_fee' => 0
        ]);

        $couponDiscountService = new CouponDiscountService();
        $couponDiscountService->applyCoupon($coupon->coupon_code, $cart);
        $this->assertEquals(8, $cart->total);
    }


}
