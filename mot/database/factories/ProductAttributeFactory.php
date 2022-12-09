<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductAttribute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'variation_id' => null,
            // this wrong ... but I have no idea how to fix this.
            // Any suggestion is welcome. perhaps states ?
            // Following allows linking to product option that belongs to a different type of attribute
            'attribute_id' => Attribute::factory(),
            'option_id' => Attribute::factory(),
        ];
    }
}
