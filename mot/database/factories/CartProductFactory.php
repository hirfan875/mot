<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cart_id' => Cart::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_price' => $this->faker->numberBetween(5, 10),
            'delivery_fee' => 0,
            'currency_id' => Currency::factory(),
            'message'   => $this->faker->sentence(1)
        ];
    }
}
