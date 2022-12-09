<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attribute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $order = 0;
        return [
            'title' => $this->faker->colorName,
        ];
    }
}
