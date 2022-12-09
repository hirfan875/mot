<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'store_order_id' => StoreOrder::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_price' => $this->faker->numberBetween(5, 10),
            'delivery_fee' => 0,
            'currency_id' => Currency::factory(),
        ];
    }
}
