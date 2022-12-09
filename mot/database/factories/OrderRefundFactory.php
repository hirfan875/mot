<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderRefundFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderRefund::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status'=>array_rand([0,1,2,3,4,5,6,7]),
            'customer_id' => Customer::factory(),
            // this is not accurate ... CustomerAddress  should be created with Customer
            'address_id' => CustomerAddress::factory(),
            'sub_total' => $this->faker->numberBetween(10, 100),
            'delivery_fee' => $this->faker->numberBetween(5, 10),
            'tax' => $this->faker->numberBetween(1,5),
            'coupon_id' => Coupon::factory(),
            'currency_id' => Currency::factory(),
            'address' => $this->faker->address,
        ];
    }
}
