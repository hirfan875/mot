<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class CouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => true,
            'coupon_code' => strtoupper($this->faker->unique()->word(1)),
            'start_date' => now()->today(),
            'end_date' => now()->tomorrow(),
            'type' => Arr::random(['fixed', 'percentage']),
            'discount' => Arr::random([5, 10, 15, 20]),
            'usage_limit' => Coupon::COUPON_USAGE_UNLIMITED,
            'applies_to' => Coupon::COUPON_APPLY_TO_ALL_PRODUCTS
        ];
    }
}
