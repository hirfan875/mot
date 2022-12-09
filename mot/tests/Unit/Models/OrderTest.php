<?php
namespace Tests\Unit\Models;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreOrder;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;


    public function testOrderCustomer()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();
        /** @var Order $order */
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $this->assertEquals($customer->id, $order->customer->id);
    }

    public function testOrderCoupon()
    {
        /** @var Coupon $coupon */
        $coupon = Coupon::factory()->create();
        /** @var Order $order */
        $order = Order::factory()->create(['coupon_id' => $coupon->id]);

        $this->assertEquals($coupon->id, $order->coupon->id);
    }

    /**
     * TODO MOve Order Status Transformation to StoreOrder
     */

    public function testOrderStatusTransition()
    {
        /** @var Order $order */
        $order = Order::factory()->create(['status' => Order::READY_ID]);
        $order->changeStatus(Order::SHIPPED_ID);

        $this->assertEquals(Order::SHIPPED_ID, $order->status);
    }


    public function testSetInvalidOrderStatus()
    {
        $this->expectException(\Exception::class);
        /** @var Order $order */
        $order = Order::factory()->create(['status' => Order::UNIITIATED_ID]);
        $order->changeStatus(121);

        $this->assertEquals(Order::READY_ID, $order->status);
    }

    public function testInvalidOrderStatusTransition()
    {
        $this->expectException(\Exception::class);
        /** @var Order $order */
        $order = Order::factory()->create(['status' => Order::UNIITIATED_ID]);
        $order->changeStatus(Order::READY_ID);

        $this->assertEquals(Order::READY_ID, $order->status);
    }


    public function testOrderItemHasProducts()
    {
        $product = Product::factory()->create(['title' => 'Sample Product']);
        $orderItem = OrderItem::factory()->create(['product_id' => $product->id]);
        $this->assertEquals('Sample Product', $orderItem->product->title);
    }


    public function testOrderGetStatus()
    {
        /** @var Order $order */
        $order = Order::factory()->create(['status' => Order::READY_ID]);

        $this->assertEquals('Ready', $order->getStatus());
    }

    public function testOrderCurrency()
    {
        /** @var Order $order */
        $order = Order::factory()->create();

        $this->assertEquals('TRY', $order->currency->code);
    }

    public function testOrderLastStatusUpdate()
    {
        /** @var Order $order */
        $order = Order::factory()->create(['order_date' => today()]);

        $this->assertEquals(today()->format('m-d-y'), $order->getLastStatusUpdateDate()->format('m-d-y'));
    }


}
