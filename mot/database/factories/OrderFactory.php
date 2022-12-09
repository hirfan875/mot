<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status'=>array_rand([2,3,4,5,6,7]), // use constant
            'customer_id' => Customer::factory(),
            'order_number' => $this->faker->numerify('1##-###-###'),
            // this is not accurate ... CustomerAddress  should be created with Customer
            'address_id' => CustomerAddress::factory(),
            'sub_total' => $this->faker->numberBetween(10, 100),
            'delivery_fee' => $this->faker->numberBetween(5, 10),
            'tax' => $this->faker->numberBetween(1,5),
            'coupon_id' => Coupon::factory(),
            'currency_id' => 1 ,// Currency::factory(),
            'order_date' => Carbon::now() ,
            'address' => $this->faker->address,
            'order_date' => $this->faker->dateTime,
        ];
    }
}
