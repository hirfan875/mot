<?php

namespace Tests\Feature\Admin;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Admin Visit Edit Page of Store
     *
     * @return void
     */
    public function testAdminCanVisitStoreEditTest()
    {
        /** @var Store $store */
        $store = Store::factory()->create(['status' => true , 'is_approved' => true]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.stores.edit', [$store->id]));

        $response->assertStatus(200);
        $response->assertSee($store->name);
    }

    public function testAdminCanEditStoreTest()
    {
        /** @var Store $store */
        $store = Store::factory()->create(['status' => true , 'is_approved' => true]);
        $user = User::factory()->create();
        $response = $this->withMiddleware(VerifyCsrfToken::class)->actingAs($user)->post(route('admin.stores.edit', [$store->id]), [
            "_token" => "Zqw16ypc5LWLJUsrPxEznuGX9JWjyUiqRKQ6l61l",
            "type" => "0",
            "name" => "Flower Shop",
            "phone" => "1233445",
            "address" => "One Car Drive",
            "city" => "Istanbul",
            "state" => "Istanbul",
            "country" => "1",
            "zipcode" => "23232",
            "commission" => null,
            "tax_id" => null,
            "tax_id_type" => null,
            "tax_office" => 'tax-office-dummy',
            "identity_no" => '123456798',
        ]);
        $response->assertStatus(302);
    }

}
