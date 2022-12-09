<?php

namespace Tests\Feature\Customer\MyAccount;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreOrder;
use App\Models\User;
use App\Models\Wishlist;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrdersTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;


    public function test_customer_can_see_list_of_orders()
    {
        /** @var Order $order */
        $order = Order::factory()->create();
        $storeOrder = StoreOrder::factory()->create(['order_id' => $order->id]);
        /** @var OrderItem $orderItem */
        $orderItem = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);

        $response = $this->actingAs($order->customer, 'customer')
            ->get(route('order-history'), ['accept'=> 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText(html_entity_decode($orderItem->product->title));
    }

    public function test_customer_can_see_list_of_cancelled_orders()
    {
        /** @var Order $order */
        $order = Order::factory()->create(['status' => Order::CANCELLED_ID]);
        $storeOrder = StoreOrder::factory()->create(['status' => Order::CANCELLED_ID , 'order_id' => $order->id]);
        /** @var OrderItem $orderItem */
        $orderItem = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);

        $response = $this->actingAs($order->customer, 'customer')
            ->get(route('cancellation'), ['accept'=> 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText(html_entity_decode($orderItem->product->title));
    }

    public function test_customer_can_see_link_to_order()
    {
        /** @var Order $order */
        $order = Order::factory()->create();
        $storeOrder = StoreOrder::factory()->create(['order_id' => $order->id]);
        /** @var OrderItem $orderItem */
        $orderItem = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);

        $response = $this->actingAs($order->customer, 'customer')
            ->get(route('order-history'));

        $response->assertStatus(200);
        $response->assertSee(route('order-detail' , $storeOrder->id));
    }

    public function test_customer_can_see_order_details()
    {
        /** @var Order $order */
        $order = Order::factory()->create();
        /** @var StoreOrder $storeOrder */
        $storeOrder = StoreOrder::factory()->create(['order_id' => $order->id]);
        /** @var OrderItem $orderItem */
        $orderItem = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);

        $response = $this->actingAs($order->customer, 'customer')
            ->get(route('order-detail' ,$storeOrder->id ));

        $response->assertStatus(200);
        $response->assertSee($storeOrder->order_number);
        $response->assertSee($orderItem->product->title);
    }

    public function test_customer_can_cancel_order()
    {
        /** @var Order $order */
        $order = Order::factory()->create( ['status'=> StoreOrder::PAID_ID]);
        $storeOrder = StoreOrder::factory()->create(['order_id' => $order->id , 'status'=> Order::PAID_ID]);
        /** @var OrderItem $orderItem */
        $orderItem = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);

        $response = $this
            ->actingAs($order->customer, 'customer')
            ->json('POST', route('cancel-order' ,
                ['store_order_id'=> $storeOrder->id, 'reason' => 'reason ' , 'notes'=> 'notes' ]
            ));

        $response->assertStatus(200);
        $response->assertSee('success');
    }

    public function test_customer_can_view_tracking_of_shipped_or_delivered()
    {
        /** @var Order $order */
        $order = Order::factory()->create( ['status'=> StoreOrder::PAID_ID]);
        $storeOrder = StoreOrder::factory()->create(['order_id' => $order->id , 'status'=> Order::SHIPPED_ID]);
        /** @var OrderItem $orderItem */
        $orderItem = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);

        $response = $this
            ->actingAs($order->customer, 'customer')
            ->json('GET', route('track-package' ,$storeOrder->id));

        $response->assertStatus(200);
    }
}


