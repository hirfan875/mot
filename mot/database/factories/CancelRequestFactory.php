<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CancelRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CancelRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status'=>array_rand([0,1]),
            'order_id' => Order::factory(),
            'quantity' => $this->faker->numberBetween(0,10),
            'order_item_id' => OrderItem::factory(),
            'notes' => $this->faker->sentence,
        ];
    }
}
