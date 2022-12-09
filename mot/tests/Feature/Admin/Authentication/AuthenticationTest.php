<?php

namespace Tests\Feature\Admin\Authentication;

use App\Models\Customer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_login_screen_can_be_rendered()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate_using_the_login_screen()
    {
        $customer = User::factory()->create(['password' => Hash::make('password')]);

        $response = $this->post('/admin/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('web');
    }

    public function test_admin_authentication_redirects_to_home()
    {
        /** @var User $user */
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $response = $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(RouteServiceProvider::ADMIN_DASHBOARD);
    }

    public function test_admin_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
