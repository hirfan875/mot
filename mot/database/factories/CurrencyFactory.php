<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Currency::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => true,
            'title' => $this->faker->currencyCode,
            'code' => $this->faker->currencyCode,
            'symbol' => $this->faker->currencyCode,
            'symbol_position' => array_rand(['left', 'right']),
            'thousand_separator' => ',',
            'decimal_separator' => '.',
        ];
    }
}
