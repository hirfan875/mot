<?php

namespace Tests\Feature\Customer\Authentication;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/login-register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->json('POST', '/customer-register', [
            'register_name' => $this->faker->name,
            'register_email' => $this->faker->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertEquals('{"success":"true"}', $response->content());;
    }

    public function test_new_users_are_authenticated()
    {
        $response = $this->post('/customer-register', [
            'register_name' => $this->faker->name,
            'register_email' => $this->faker->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ], ['Accept: application/json']);

        $this->assertAuthenticated('customer');
    }
}
