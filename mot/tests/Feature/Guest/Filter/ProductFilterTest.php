<?php

namespace Tests\Feature\Guest\Filter;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductFilterTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCategoryPageTest()
    {
        /** @var Product $product */
        $category  = Category::factory()->create();
        /** @var Product $product */
        $product = Product::factory()->create();
        $product->categories()->attach($category);
        $response = $this->get(route('category' , $product->categories->first()->slug));

        $response->assertStatus(200);
        $response->assertSee($product->title);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCategoryPageImageTest()
    {
        /** @var Product $product */
        $product = Product::where('title', 'square house')->first();

        /** @var Product $product */
        $category  = Category::factory()->create();
        $product->categories()->attach($category);
        $response = $this->get(route('category' , $product->categories->first()->slug));

        $response->assertStatus(200);
        $response->assertSee($product->title);
    }

}
