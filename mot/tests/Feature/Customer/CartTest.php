<?php

namespace Tests\Feature\Customer;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class CartTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function test_customer_should_be_able_to_add_to_cart()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();

        /** @var Product $product */
        $product = Product::factory()->create(['promo_price'=> 10 ,'stock' => 100]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('add-to-cart'),
                [
                    'product_id'=> $product->id,
                    'quantity'=> '1'
]
            );
        $response->assertStatus(200);
    }


    /**
     * @tahir this test has started failing ... please find why. It looks like it has to do with soft-deletes ?
     * If a product is removed from the cart
     */
    public function test_customer_should_be_able_to_remove_cart()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();
        /** @var Cart $cart */
        $cart = Cart::factory()->create(['session_id' => '1234567890', 'status' => Cart::OPEN_ID]);

        /** @var CartProduct $cartProduct */
        $cartProduct = CartProduct::factory()->create(['cart_id' => $cart->id]);
        $response = $this
            ->withSession(['cart-session-id' => $cart->session_id])
            ->actingAs($customer, 'customer')
            ->post(route('remove-cart-item'),
                ["id" =>$cartProduct->id]
            );

        $response->assertStatus(200);
        $cart->refresh();
        $this->assertCount(0, $cart->cart_products);
        $this->assertEquals(0, $cart->total);
    }


    public function test_customer_should_not_be_able_to_add_out_of_stock_item()
    {
        /** @var Product $product */
        $product = Product::factory()->create(['stock' =>0]);
        /** @var Customer $customer */
        $customer = Customer::factory()->create();
        /** @var Cart $cart */
        // @Irfan Cart originally belonged to customers, the field was nullable.
        // I wonder why was this field removed.
        //  'customer_id' => $customer->id
        $cart = Cart::factory()->create(['session_id' => '1234567890',]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('add-to-cart'),
                [
                    'product_id'=> $product->id,
                    'quantity'=> '1'
                ]
            );

        $response->assertStatus(400);
        $this->assertCount(0, $cart->cart_products);
    }


    public function test_customer_should_not_be_able_to_parents_of_a_variant()
    {
        /** @var Product $product */
        $product = Product::factory()->create(['type'=>'variable' , 'parent_id' => null]);
        /** @var Customer $customer */
        $customer = Customer::factory()->create();
        /** @var Cart $cart */
        $cart = Cart::factory()->create();

        $response = $this
            ->withSession(['cart-session-id' => $cart->session_id])
            ->actingAs($customer, 'customer')
            ->post(route('add-to-cart'),
        [
            'product_id'=> $product->id,
            'quantity'=> '1'
        ]
    );

        $response->assertStatus(400);
        $this->assertCount(0, $cart->cart_products);
    }
}




