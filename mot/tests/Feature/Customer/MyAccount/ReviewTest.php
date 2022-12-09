<?php

namespace Tests\Feature\Customer\MyAccount;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\StoreOrder;
use App\Models\StoreReview;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;


    public function test_customer_can_see_list_of_product_reviews()
    {
        /** @var ProductReview $productReview */
        $productReview = ProductReview::factory()->create(['comment' => 'My Product Review']);

        $response = $this->actingAs($productReview->customer, 'customer')
            ->get(route('customer-product-reviews'), ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText(html_entity_decode($productReview->comment));
    }

    public function test_customer_can_not_see_product_reviews_by_others_in_their_list()
    {
        /** @var ProductReview $myProductReview */
        $myProductReview = ProductReview::factory()->create(['comment' => 'My Review']);
        /** @var ProductReview $productReview */
        $productReview = ProductReview::factory()->create(['comment' => 'Some one else Review']);

        $response = $this->actingAs($myProductReview->customer, 'customer')
            ->get(route('customer-product-reviews'), ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertDontSeeText(html_entity_decode($productReview->comment));
    }

    public function test_customer_can_see_a_specific_review()
    {
        /** @var ProductReview $myProductReview */
        $myProductReview = ProductReview::factory()->create(['comment' => 'My Review']);

        $response = $this->actingAs($myProductReview->customer, 'customer')
            ->get(route('show-customer-product-reviews', $myProductReview->id), ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText(html_entity_decode($myProductReview->comment));
    }


    public function test_customer_can_create_product_reviews()
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);
        $storeOrder = StoreOrder::factory()->create(['order_id' => $order->id]);
        /** @var OrderItem $orderItem */
        $orderItem = OrderItem::factory()->create(['store_order_id' => $storeOrder->id]);
        $response = $this->actingAs($customer, 'customer')
            ->json('post', route('create-customer-product-reviews'), [
                'comment' => 'My Review Comments',
                'rating' => 2,
                'order_item_id' => $orderItem->id,
                'gallery' =>''
            ]);

        $response->assertStatus(200); // should be 201
        $response->assertSeeText('My Review Comments');
        $response->assertSeeText($customer->id);
        $response->assertSeeText($orderItem->id);
    }

    public function test_customer_can_see_list_of_store_reviews()
    {
        /** @var StoreReview $storeReview */
        $storeReview = StoreReview::factory()->create(['comment' => 'My Store Review']);

        $response = $this->actingAs($storeReview->customer, 'customer')
            ->get(route('customer-store-reviews'), ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText(html_entity_decode($storeReview->comment));
    }

    public function test_customer_can_not_see_store_reviews_by_others_in_their_list()
    {
        /** @var StoreReview $myStoreReview */
        $myStoreReview = ProductReview::factory()->create(['comment' => 'My Review']);
        /** @var StoreReview $storeReview */
        $storeReview = ProductReview::factory()->create(['comment' => 'Some one else Review']);

        $response = $this->actingAs($myStoreReview->customer, 'customer')
            ->get(route('customer-store-reviews'), ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertDontSeeText(html_entity_decode($storeReview->comment));
    }

    public function test_customer_can_see_a_specific_store_review()
    {
        /** @var StoreReview $myStoreReview */
        $myStoreReview = StoreReview::factory()->create(['comment' => 'My Review']);

        $response = $this->actingAs($myStoreReview->customer, 'customer')
            ->get(route('show-customer-store-reviews', $myStoreReview->id), ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText(html_entity_decode($myStoreReview->comment));
    }
    public function test_customer_can_create_store_reviews()
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);
        $storeOrder = StoreOrder::factory()->create(['order_id' => $order->id]);
        $response = $this->actingAs($customer, 'customer')
            ->json('post', route('create-customer-store-reviews'), [
                'comment' => 'My Review Comments',
                'rating' => 2,
                'store_id' => $storeOrder->store_id,
                'store_order_id' => $storeOrder->id,
            ]);

        $response->assertStatus(201);
        $response->assertSeeText('My Review Comments');
        $response->assertSeeText($customer->id);
        $response->assertSeeText($storeOrder->id);
    }
}


