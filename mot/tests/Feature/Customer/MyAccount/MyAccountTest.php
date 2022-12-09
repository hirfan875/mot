<?php

namespace Tests\Feature\Customer\MyAccount;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MyAccountTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function test_customer_can_access_my_account_page()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->get(route('my-account'));

        $response->assertStatus(200);
        $response->assertSee($customer->name);
        $response->assertSee($customer->email);
        $response->assertSee($customer->phone);
    }

    public function test_customer_can_update_profile()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();
        $newPhone = $this->faker->phoneNumber;
        $response = $this->actingAs($customer, 'customer')
            ->post(route('update-profile'), [
                'name' => 'updated name',
                'birthday' => '2000-01-01',
                'phone' => $newPhone,
            ], ['accept' => 'application/json']);
        $response->assertStatus(200);
    }

    public function test_api_customer_can_access_my_account_page()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->get(route('my-account'),['accept'=> 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText(html_entity_decode($customer->name));
        $response->assertSeeText($customer->email);
        $response->assertSeeText($customer->phone);
    }
}
