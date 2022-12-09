<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\City;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $order = 0;
        return [
            'title' => $this->faker->city,
        ];
    }
}
