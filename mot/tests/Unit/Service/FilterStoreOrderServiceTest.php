<?php


namespace Tests\Unit\Service;


use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\ReturnOrderItems;
use App\Models\ReturnRequest;
use App\Models\StoreOrder;
use App\Models\StoreReview;
use App\Service\FilterStoreOrderService;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FilterStoreOrderServiceTest extends TestCase
{
    use DatabaseTransactions;
    public function testGetAllOrders()
    {
        $this->markTestIncomplete();
        $orders = StoreOrder::factory()->create(['status' => StoreOrder::PAID_ID]);
        $storeOrderService = new FilterStoreOrderService();
        $this->assertCount(1, $storeOrderService->get());
    }
}
