<?php

namespace Tests\Feature\Guest;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductPageTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testProductPageTest()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $response = $this->get($product->getViewRoute());

        $response->assertStatus(200);
        $response->assertSee($product->title);
    }
}
