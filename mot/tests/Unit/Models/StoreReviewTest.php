<?php

namespace Tests\Unit\Models;

use App\Models\Store;
use App\Models\StoreReview;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StoreReviewTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * test if we have relations right
     *
     * @return void
     */
    public function testStoreReviewsRelations()
    {
        /** @var Store $store */
        $store = Store::factory()->create();
        $review = StoreReview::factory()->create(['rating' => 4, 'store_id' =>$store->id]);
        $this->assertCount(1, $store->reviews);
        $this->assertEquals($store->id, $review->store->id);
    }


    public function testPositiveRating()
    {

  //      Arrange
        /** @var Store $store */
        $store = Store::factory()->create();
        $review = StoreReview::factory()->create(['rating' => 4, 'store_id' =>$store->id]);
        $review = StoreReview::factory()->count(4)->create(['rating' => 3, 'store_id' =>$store->id]);
//$this->assertCount(1, $store->reviews);
// Act
/// Assert
        $this->assertEquals(20, $store->getPositiveRatingPercent());
    }


    public function testPositiveRatingBeforeYear()
    {

  //      Arrange
        /** @var Store $store */
        $store = Store::factory()->create();
        $review = StoreReview::factory()->create([
             'rating' => 4,
             'store_id' => $store->id,
             'created_at' => Carbon::now()->subYear()->subWeek()
         ]);
        $review = StoreReview::factory()->count(4)->create(['rating' => 3, 'store_id' =>$store->id]);
        $this->assertEquals(20, $store->getPositiveRatingPercent());
    }



    public function testLifetimeRatingCount()
    {
        /** @var Store $store */
        $store = Store::factory()->create();
        $review = StoreReview::factory()->count(10)->create(['rating' => 2, 'store_id' =>$store->id]);
        $this->assertEquals(10, $store->lifetimeRatingCount());
    }

    public function testGetRatingAttribute()
    {
        $store = Store::factory()->create();
        $review = StoreReview::factory()->count(100)->create(['rating' => 5, 'store_id' =>$store->id]);
        $this->assertEquals(5, $store->getRatingAttribute());
    }

    public function testGetPositiveRating()
    {
        /** @var Store $store */
        $store = Store::factory()->create();
        $review = StoreReview::factory()->count(100)->create(['rating' => 4, 'store_id' =>$store->id]);
        $this->assertEquals(100, $store->getPositiveRatingPercent());
    }

}
