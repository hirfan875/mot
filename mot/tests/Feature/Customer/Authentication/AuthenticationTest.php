<?php

namespace Tests\Feature\Customer\Authentication;

use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login-register');

        $response->assertStatus(200);
    }

    public function test_customers_can_authenticate_using_the_login_screen()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create(['password' => Hash::make('password')]);

        $response = $this->post('/customer-login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('customer');
    }

    public function test_customers_authentication_redirects_to_home()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create(['password' => Hash::make('password')]);
        $response = $this->post('/customer-login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_customers_can_not_authenticate_with_invalid_password()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();

        $this->post('/customer-login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
