<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductReview::class;

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
            'order_item_id' => OrderItem::factory(),
            'is_approved' => true,
        ];
    }
}
