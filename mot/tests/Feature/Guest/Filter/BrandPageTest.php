<?php

namespace Tests\Feature\Guest\Filter;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Service\FilterProductsService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BrandPageTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBrandPageTest()
    {
        /** @var Brand $brand */
        $brand = Brand::factory()->create();
        /** @var Product $product */
        $product = Product::factory()->create(['brand_id'=> $brand->id]);
        $response = $this->get(route('brand' ,[$brand->slug]));

        $response->assertStatus(200);
        $response->assertSee($product->title);
    }
}
