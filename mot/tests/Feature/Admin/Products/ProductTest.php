<?php

namespace Tests\Feature\Admin\Products;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Store;

class ProductTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;


    public function test_admin_can_see_add_product_form()
    {
        $admin = User::factory()->create();



        $response = $this->actingAs($admin)->get('/admin/products/add');


        $response->assertStatus(200);
    }

    public function test_admin_can_add_product()
    {
        $admin = User::factory()->create();
        $brand = Brand::factory()->create();
        $store = Store::factory()->create();



        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($admin)
            ->post(route('admin.products.add'), [
                'title' => 'Test Product',
                'type' => 'simple',
                'sku' => 'simple-sku',
                'stock' => 1,
                'brand' => $brand->id,
                'store' => $store->id,
                'price' => 10,
                'categories' => [Category::first()->id],
                'discount' => 10,
                'discount_type' => 'percentage',
                'delivery_fee' => 100,
                'free_delivery' => false,
                'data' => '',
                'meta_title' => '',
                'meta_desc' => '',
                'meta_keyword' => '',

        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }

    public function test_admin_authentication_redirects_to_home()
    {
        /** @var User $user */
        $user = User::factory()->create(['password' => \Hash::make('password')]);
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
