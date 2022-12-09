<?php

namespace Tests\Feature\Customer\MyAccount;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChangePasswordTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function test_customer_can_access_change_password_page()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->get(route('change-password'),['accept'=> 'application/json']);

        $response->assertStatus(200);
        $response->assertSeeText(html_entity_decode($customer->name));
        $response->assertSeeText($customer->email);
        $response->assertSeeText($customer->phone);
    }
}
