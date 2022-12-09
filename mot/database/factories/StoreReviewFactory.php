<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreData;
use App\Models\StoreOrder;
use App\Models\StoreReview;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreReview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment' => $this->faker->paragraph,
            'rating' => 3,
            'customer_id' => Customer::factory(),
            'store_id' => Store::factory()->has(StoreData::factory()->count(1), 'store_data')->create(),
            'store_order_id' => StoreOrder::factory(),
            'is_approved' => true,
        ];
    }
}
