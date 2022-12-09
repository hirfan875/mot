<?php

namespace Tests\Feature\Customer\MyAccount;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddressTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function test_customer_can_see_list_of_addresses()
    {
        $customer = Customer::factory()->create();

        /** @var CustomerAddress $customerAddress */
        $customerAddress = CustomerAddress::factory()->create(['customer_id'=>$customer->id]);

        $response = $this->actingAs($customer, 'customer')
                         ->get(route('list-address'),['accept'=> 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText(html_entity_decode($customerAddress->name));
    }

    public function test_customer_can_add_an_address()
    {
        $customer = Customer::factory()->create();

        /** @var CustomerAddress $customerAddress */
        CustomerAddress::factory()->create(['customer_id'=>$customer->id]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('add-address'),  [
                'name' => $this->faker->name,
                'phone' => '+902165190010',
                'full' => '+902165190010',
                'zipcode' => $this->faker->postcode,
                'address' => $this->faker->address,
                'address2' => '',
                'address3' => '',
                'city' => $this->faker->city,
                'state' => $this->faker->state,
                'country' => $this->faker->country,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('list-address'));
        $response->assertSessionHas('message' , __('Address saved successfully.'));

    }

    public function test_customer_add_an_address()
    {
        $customer = Customer::factory()->create();

        /** @var CustomerAddress $customerAddress */
        CustomerAddress::factory()->create(['customer_id'=>$customer->id]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('add-address'),  [
                'name' => '121212',
                'phone' => '121212',
                'zipcode' => '1212',
                'address' => '1212',
                'city' => '1212',
                'state' => '1212',
                'country' => '12121',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('list-address'));
        $response->assertSessionHas('message' , __('Unable to add address.'));

    }


    public function test_customer_should_not_be_allowed_to_add_an_invalid_phone_when_adding_address()
    {
        $customer = Customer::factory()->create();

        /** @var CustomerAddress $customerAddress */
        $customerAddress = CustomerAddress::factory()->create(['customer_id'=>$customer->id]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('add-address'),  [
                'name' => $this->faker->sentence,
                'phone' => $this->faker->word,
                'zipcode' => $this->faker->postcode,
                'address' => $this->faker->address,
                'city' => $this->faker->city,
                'state' => $this->faker->state,
                'country' => $this->faker->country,
            ],['accept'=> 'application/json']);

        $response->assertStatus(302);
        $response->assertRedirect(route('list-address'));
        $response->assertSessionHas('message' , __('Unable to add address.'));
    }
}
