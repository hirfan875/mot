<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product_title = $this->faker->unique()->jobTitle;
        return [
            'store_id' => Store::factory()->has(StoreData::factory()->count(1), 'store_data')->create(),
            'brand_id' => Brand::factory(),
            'title' => $product_title,
            'slug' => Str::slug($product_title),
            'type' => Product::TYPE_SIMPLE,
            'sku' => Str::random(10),
            'price' => $this->faker->numberBetween(100,10000),
            'delivery_fee' => $this->faker->numberBetween(10,100),
            'discount' => 15,
            'discount_type' => 'percentage',
            'promo_price' => 210,
            'promo_source_type' => Product::class,
            'promo_source_id' => null,
            'stock' => $this->faker->numberBetween(0,3),
            'free_delivery' => false,
            'image' => null,
            'data' => $this->faker->paragraph
        ];
    }
}
