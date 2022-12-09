<?php
namespace Tests\Unit\Models;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderItemTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;


    public function testOrderItemProduct()
    {
        /** @var OrderItem $orderItem */
        $orderItem = OrderItem::factory()->create();

        $this->assertNotNull($orderItem->product);
    }
}
