<?php

namespace Tests\Unit\Service;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\StoreOrder;
use App\Models\StoreReview;
use App\Service\ReviewService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewServiceTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;


    public function testGetReviewsByCustomer()
    {
        /** @var ProductReview $productReview */
        $productReview = ProductReview::factory()->create(['comment' => 'My Product Review']);
        $reviewService = new ReviewService();
        $reviews = $reviewService->customerProductReviews($productReview->customer);
        $this->assertCount(1, $reviews);
    }

    public function testCreateReviewsByCustomer()
    {
        $customer = Customer::factory()->create();
        $order  = Order::factory()->create(['customer_id' => $customer->id]);
        $storeOrder  = StoreOrder::factory()->create(['order_id' => $order->id]);
        $orderItem  = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);
        $reviewService = new ReviewService();
        /** @var ProductReview $productReview */
        $productReview = $reviewService->createProductReviewByCustomer($customer, [
            'comment' => 'My Bad Review' ,
            'rating' => 4,
            'order_item_id' => $orderItem->id,
            'gallery' => '',    // @saad WHY an empty strin gis required
        ]);
        $this->assertEquals($customer->id, $productReview->customer_id);
        $this->assertEquals(4, $productReview->rating);
        $this->assertEquals($orderItem->id, $productReview->order_item_id);
        $this->assertEquals('My Bad Review', $productReview->comment);
        $this->assertFalse($productReview->is_approved);
    }

    /**
     *
     */
    public function testCreateReviewsInvalidRating()
    {
        $this->expectException(\Exception::class);
        $customer = Customer::factory()->create();
        $order  = Order::factory()->create(['customer_id' => $customer->id]);
        $storeOrder  = StoreOrder::factory()->create(['order_id' => $order->id]);
        $orderItem  = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);
        $reviewService = new ReviewService();
        /** @var ProductReview $productReview */
        $productReview = $reviewService->createProductReviewByCustomer($customer, [
            'comment' => 'My Bad Review' ,
            'rating' => 6,
            'order_item_id' => $orderItem->id,
        ]);
    }

    /**
     *
     */
    public function testCreateReviewsInvalidOrderItemId()
    {
        $this->expectException(\Exception::class);
        $customer = Customer::factory()->create();
        $orderItem  = OrderItem::factory()->create();
        $reviewService = new ReviewService();
        /** @var ProductReview $productReview */
        $productReview = $reviewService->createProductReviewByCustomer($customer, [
            'comment' => 'My Bad Review' ,
            'rating' => 6,
            'order_item_id' => $orderItem->id,
        ]);
    }

    /**
     *
     */
    public function testGetStoreReviewsByCustomer()
    {
        /** @var StoreReview $storeReview */
        $storeReview = StoreReview::factory()->create(['comment' => 'My Product Review']);
        $reviewService = new ReviewService();
        $reviews = $reviewService->customerStoreReviews($storeReview->customer);
        $this->assertCount(1, $reviews);
    }

    public function testCreateStoreReviewsByCustomer()
    {
        $customer = Customer::factory()->create();
        $order  = Order::factory()->create(['customer_id' => $customer->id]);
        $storeOrder  = StoreOrder::factory()->create(['order_id' => $order->id]);
        $orderItem  = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);
        $reviewService = new ReviewService();
        /** @var StoreReview $storeReview */
        $storeReview = $reviewService->createStoreReviewByCustomer($customer, [
            'comment' => 'My Bad Store Review' ,
            'rating' => 1,
            'store_order_id' => $storeOrder->id,
            'store_id' => $storeOrder->store_id,
        ]);
        $this->assertEquals($customer->id, $storeReview->customer_id);
        $this->assertEquals(1, $storeReview->rating);
        $this->assertEquals($storeOrder->id, $storeReview->store_order_id);
        $this->assertEquals('My Bad Store Review', $storeReview->comment);
        $this->assertFalse($storeReview->is_approved);
    }
}


