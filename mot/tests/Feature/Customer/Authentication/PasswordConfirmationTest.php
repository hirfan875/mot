<?php

namespace Tests\Feature\Customer\Authentication;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')->get('/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $customer = Customer::factory()->create();
        // TODO it looks like we can confirm password even when using other
        // types of users and other types of guards
        // This needs to be fixed.

        $response = $this->actingAs($customer, 'customer')->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer,'customer')->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
