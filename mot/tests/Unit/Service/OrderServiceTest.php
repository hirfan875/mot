<?php

namespace Tests\Unit\Service;

use App\Jobs\CancelOrder;
use App\Jobs\RefundStoreOrder;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreOrder;
use Illuminate\Support\Facades\Bus;
use App\Service\OrderService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetCustomerOrder()
    {
        $order = Order::factory()->create();
        $storeOrder = StoreOrder::factory()->create(['order_id'=> $order->id]);
        $orderService = new OrderService();
        $this->assertCount(1, $orderService->getCustomersOrderList($order->customer));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetCustomerCancelledOrder()
    {
        $order = Order::factory()->create(['status' => Order::CANCELLED_ID]);
        $storeOrder = StoreOrder::factory()->create(['status' => Order::CANCELLED_ID , 'order_id'=> $order->id]);
        $orderService = new OrderService();
        $this->assertCount(1, $orderService->getCustomersCancelledOrder($order->customer));
    }

    public function testCartProductToOrderItem()
    {
        $product = Product::factory()->create();
        $storeOrder = StoreOrder::factory()->create();
        /** @var CartProduct $cartProduct */
        $cartProduct = CartProduct::factory()->create([
            'product_id'=>$product->id,
            'quantity' => $product->stock
        ]);
        $orderItem = $cartProduct->toOrderItem($storeOrder);
        $this->assertNotNull($cartProduct->product->title);
        $this->assertIsObject($orderItem);
    }


    /**
     * Converting an empty cart to an order will throw exception
     * @throws \Exception
     */
    public function testCreateOrder()
    {
        $this->expectException(\Exception::class);
        $customerAddress = CustomerAddress::factory()->create();
        $cart = Cart::factory()->create();
        $orderService = new OrderService();
        $order = $orderService->createOrder($cart, $customerAddress);
    }

    public function testCreateOrderHasProductsCopiedFromCart(){
        $product = Product::factory()->create();
        /** @var CartProduct $cartProduct */
        $cartProduct = CartProduct::factory()->create([
            'product_id' => $product->id,
            'quantity' => $product->stock
        ]);
        $this->assertCount(1,$cartProduct->cart->cart_products);
        $customerAddress = CustomerAddress::factory()->create();
        $orderService = new OrderService();

        $order = $orderService->createOrder($cartProduct->cart , $customerAddress);
        $this->assertIsObject($order);
        $this->assertIsObject($order->store_orders);
        $this->assertCount(1,$order->store_orders);
        $this->assertEquals($product->id, $order->store_orders->first()->order_items->first()->product_id);
    }

    /**
     * Test when cart has multiple products
     * @throws \Exception
     */
    public function testCreateOrderHasMultipleProductsCopiedFromCart()
    {
        $cart = Cart::factory()->create([
            'session_id' => '123456789'
        ]);

        $product = Product::factory()->create(['stock' => 1 , 'store_id' => Store::first()]);
        /** @var CartProduct $cartProduct */
        $cartProduct = CartProduct::factory()->create([
            'product_id' => $product->id,
            'quantity' => $product->stock,
            'cart_id'=> $cart->id
        ]);

        $product = Product::factory()->create(['stock' => 1 , 'store_id' => Store::first()]);
        /** @var CartProduct $cartProduct */
        $cartProduct = CartProduct::factory()->create([
            'product_id' => $product->id,
            'quantity' => $product->stock,
            'cart_id'=> $cart->id
        ]);
        $this->assertNotNull($cart->sub_total);

        $this->assertCount(2,$cartProduct->cart->cart_products);
        $customerAddress = CustomerAddress::factory()->create();
        $orderService = new OrderService();

        $order = $orderService->createOrder($cart , $customerAddress);
        $this->assertIsObject($order);
        $this->assertIsObject($order->store_orders);
        $this->assertCount(1,$order->store_orders);
        $this->assertCount(2,$order->store_orders->first()->order_items);
    }

    public function testCreateOrderDecrementsStock(){
        $product = Product::factory()->create(['stock' => 2]);
        $cartProduct = CartProduct::factory()->create([
            'product_id'=>$product->id,
            'quantity' => 1
        ]);
        $customerAddress = CustomerAddress::factory()->create();
        $orderService = new OrderService();

        $order = $orderService->createOrder($cartProduct->cart , $customerAddress);
        $this->assertEquals(1, $product->refresh()->stock);
    }

    public function testCreateOrderThrowsWhenNotEnoughStock(){
        $this->expectException(\Exception::class);
        $product = Product::factory()->create();
        /** @var Cart $cart */
        $cart = Cart::factory()->create();
        $cartProduct = CartProduct::factory()->create([
            'product_id'=>$product->id,
            'cart_id' => $cart->id,
            'quantity' => $product->stock+1
        ]);
        $cart->cart_products->add($cartProduct);
        $customerAddress = CustomerAddress::factory()->create();
        $orderService = new OrderService();

        $order = $orderService->createOrder($cart , $customerAddress);
    }
    public function testCreateOrderDoesNotDecrementWhenNotEnoughStock(){
        $product = Product::factory()->create(['stock' => 2]);
        $cartProduct = CartProduct::factory()->create([
            'product_id'=>$product->id,
            'quantity' => $product->stock+1
        ]);
        $customerAddress = CustomerAddress::factory()->create();
        try{
            DB::beginTransaction();
            $orderService = new OrderService();
            $order = $orderService->createOrder($cartProduct->cart , $customerAddress);
        }catch (\Throwable $throwable){
            DB::rollBack(1);
        }
        $this->assertEquals(2,$product->refresh()->stock);
    }

    /**
     * Cancel An Order
     *
     * @return void
     */
    public function testTryToCancelOrderInInvalidState()
    {
        $this->expectException(\Exception::class);
        $order = Order::factory()->create(['status' => Order::DELIVERED_ID]);
        /** @var StoreOrder $storeOrder */
        $storeOrder = StoreOrder::factory()->create(['status' => Order::DELIVERED_ID , 'order_id'=> $order->id]);
        $orderService = new OrderService();
        $orderService->createCancelOrderRequest(
            $order->customer,
            $storeOrder->id,
            'dummy reason',
            'notes'
        );
    }

    /**
     * Cancel An Order
     *
     * @return void
     */
    public function testCancelledOrder()
    {
        Bus::fake();
        $order = Order::factory()->create(['status' => Order::PAID_ID]);
        /** @var StoreOrder $storeOrder */
        $storeOrder = StoreOrder::factory()->create(['status' => StoreOrder::PAID_ID, 'order_id'=> $order->id]);
        $orderService = new OrderService();
        $orderService->createCancelOrderRequest(
            $order->customer,
            $storeOrder->id,
            'dummy reason',
            'notes'
        );
        $storeOrder->refresh();
        $this->assertEquals(StoreOrder::CANCELLED_ID, $storeOrder->status);
        $this->assertEquals(Order::CANCELLED_ID, $storeOrder->order->status);
        Bus::assertDispatched(CancelOrder::class);
    }


    /**
     * Cancel An Order
     *
     * @return void
     */
    public function testCancelledRequestedOrder()
    {
        $order = Order::factory()->create(['status' => Order::READY_ID]);
        /** @var StoreOrder $storeOrder */
        $storeOrder = StoreOrder::factory()->create(['status' => StoreOrder::READY_ID, 'order_id' => $order->id]);
        $orderService = new OrderService();
        $orderService->createCancelOrderRequest(
            $order->customer,
            $storeOrder->id,
            'dummy reason',
            'notes'
        );
        $storeOrder->refresh();
        $this->assertEquals(StoreOrder::CANCEL_REQUESTED_ID, $storeOrder->status);
        $this->assertEquals(Order::CANCEL_REQUESTED_ID, $storeOrder->order->status);
    }

    /**
     * @throws \Exception
     */
    public function testCancelledOrderWithoutNotes()
    {
        Bus::fake();
        $order = Order::factory()->create(['status' => Order::PAID_ID]);
        /** @var StoreOrder $storeOrder */
        $storeOrder = StoreOrder::factory()->create(['status' => StoreOrder::PAID_ID, 'order_id'=> $order->id]);
        $orderService = new OrderService();
        $orderService->createCancelOrderRequest(
            $order->customer,
            $storeOrder->id,
            'dummy reason',
            null
        );
        $storeOrder->refresh();
        $this->assertEquals(StoreOrder::CANCELLED_ID, $storeOrder->status);
        $this->assertEquals(Order::CANCELLED_ID, $storeOrder->order->status);
        Bus::assertDispatched(CancelOrder::class);
    }
}
