<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Tag;
use App\Models\TrendingProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrendingProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrendingProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => 1,
            'title' => 1,
            'type' => 'category',
            'products_type' => 'tag',
            'category_id' => Category::factory(),
            'tag_id' => Tag::factory(),
        ];
    }
}
