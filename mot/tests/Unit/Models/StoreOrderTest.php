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

class StoreOrderTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;


    /**
     * TODO Order Status Transformation
     */


    public function testStoreOrderHasOrderItems()
    {
        /** @var Order $order */
        $order = Order::factory()->create();
        $storeOrder = StoreOrder::factory()->create(['status' => Order::CANCELLED_ID]);
        $orderItem = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);

        $this->assertCount(1, $storeOrder->order_items);
    }


    public function testStoreOrderToArray()
    {
        /** @var StoreOrder $storeOrder */
        $storeOrder = StoreOrder::factory()->create();
        /** @var Product $product */
        $product = Product::factory()->create(['store_id' => $storeOrder->store_id , 'title'=> 'ProductName']);
        $orderItem = OrderItem::factory()->create(['product_id' => $product->id , 'store_order_id'=> $storeOrder->id]);
        $this->assertContains('ProductName',  $storeOrder->order_items[0]->product->toArray());
    }


}
