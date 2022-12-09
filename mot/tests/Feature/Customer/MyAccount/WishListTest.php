<?php

namespace Tests\Feature\Customer\MyAccount;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WishListTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;


    public function test_customer_can_see_list_of_items_in_wishlist()
    {
        $customer = Customer::factory()->create();
        /** @var Product $products */
        $product = Product::factory()->create(['title' => 'Product Title']);
        Wishlist::factory()->create(['customer_id' => $customer->id, 'product_id' => $product->id]);

        $product = Product::factory()->create(['title' => 'Another Wishlist Item']);
        Wishlist::factory()->create(['customer_id' => $customer->id, 'product_id' => $product->id]);

        $response = $this->actingAs($customer, 'customer')
            ->get(route('wishlist'), ['accept'=> 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText('Product Title');
        $response->assertSeeText('Another Wishlist Item');
    }


    public function test_customer_can_add_an_item_in_wishlist()
    {
        $customer = Customer::factory()->create();
        /** @var Product $products */
        $product = Product::factory()->create(['title' => 'Product Title']);

        $response = $this->actingAs($customer, 'customer')
            ->get(route('add.wishlist', $product), ['accept'=> 'application/json']);

        $response = $this->actingAs($customer, 'customer')
            ->get(route('wishlist'), ['accept'=> 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText('Product Title');
    }


    public function test_customer_can_add_an_item_in_wishlist_that_is_already_in_wishlist()
    {
        $customer = Customer::factory()->create();
        /** @var Product $products */
        $product = Product::factory()->create(['title' => 'Product Title']);
        Wishlist::factory()->create(['customer_id' => $customer->id, 'product_id' => $product->id]);

        $response = $this->actingAs($customer, 'customer')
            ->get(route('add.wishlist', $product), ['accept'=> 'application/json']);
        $response->assertStatus(200);

        $response = $this->actingAs($customer, 'customer')
            ->get(route('wishlist'), ['accept'=> 'application/json']);
        $response->assertStatus(200);
        $response->assertSeeText('Product Title');
    }


    /**
     *
     */
    public function test_customer_can_remove_an_item_from_wishlist()
    {
        $customer = Customer::factory()->create();
        /** @var Product $products */
        $product = Product::factory()->create(['title' => 'Product Title']);
        $wishlist = Wishlist::factory()->create(['customer_id' => $customer->id, 'product_id' => $product->id]);


        $response = $this->actingAs($customer, 'customer')
            ->get(route('remove-from-wishlist' ,$wishlist->product->id ), ['accept' => 'application/json']);
        $response->assertStatus(200);


        $response = $this->actingAs($customer, 'customer')
            ->get(route('my-account'), ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertDontSeeText('Product Title');
    }


    /**
     *
     */
    public function test_customer_can_not_remove_an_item_from_wishlist_of_others()
    {
        /** @var Product $products */
        $product = Product::factory()->create(['title' => 'Product Title']);

        /** @var Customer $otherCustomer */
        $otherCustomer = Customer::factory()->create();
        $wishlist = Wishlist::factory()->create(['customer_id' => $otherCustomer->id, 'product_id' => $product->id]);


        $customer = Customer::factory()->create();
        $response = $this->actingAs($customer, 'customer')
            ->get(route('remove-from-wishlist' ,$wishlist->id ), ['accept' => 'application/json']);
        $response->assertStatus(400);


        $response = $this->actingAs($otherCustomer, 'customer')
            ->get(route('wishlist'), ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText('Product Title');
    }
}


