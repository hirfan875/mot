<?php

namespace Tests\Unit\Models;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * test if we have relations right
     *
     * @return void
     */
    public function testProductReviewsRelations()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $orderItem = OrderItem::factory()->create(['product_id' => $product->id]);
        $review = ProductReview::factory()->create(['rating' => 4, 'order_item_id' =>$orderItem->id]);
        $this->assertCount(1, $product->reviews);
        $this->assertEquals($product->id, $review->order_item->product->id);
    }

}
