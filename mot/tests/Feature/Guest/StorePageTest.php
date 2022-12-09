<?php

namespace Tests\Feature\Guest;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StorePageTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGuestCanVisitStorePageTest()
    {
        /** @var Store $store */
        $store = Store::factory()->create(['status' => true , 'is_approved' => true]);
        $response = $this->get($store->getViewRoute());

        $response->assertStatus(200);
        $response->assertSee($store->name);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGuestCanNotVisitNonApprovedStorePageTest()
    {
        /** @var Store $store */
        $store = Store::factory()->create(['status' => true , 'is_approved' => false]);
        $response = $this->get($store->getViewRoute());
        $response->assertStatus(404);
    }
}


