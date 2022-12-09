<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Store;
use App\Models\StoreData;
use App\Models\StoreOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => array_rand([2, 3, 4, 5, 6, 7]),
            'store_id' => Store::factory()->has(StoreData::factory()->count(1), 'store_data')->create(),
            'order_id' => Order::factory(),
            'order_number' => $this->faker->numerify('2##-###-###'),
            'sub_total' => $this->faker->numberBetween(10, 100),
            'delivery_fee' => $this->faker->numberBetween(5, 10),
            'mot_fee' => 1,
        ];
    }
}
