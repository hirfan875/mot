<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'session_id' => uniqid(),
            'status' => Cart::OPEN_ID,
            'coupon_id' => Coupon::factory(),
            'sub_total' => $this->faker->numberBetween(10, 100),
            'total' => $this->faker->numberBetween(5, 10),
            'currency_id' => Currency::factory(),
            'address_id'   => CustomerAddress::factory()
  ];
    }
}
