<?php
namespace Tests\Unit\Models;

use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Service\ProductService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;


    public function testGetProductsHasCategory()
    {
        $product = Product::factory()
            ->hasAttached(Category::factory())
            ->create();
        $this->assertCount(1, $product->categories);
        $this->assertInstanceOf(Category::class, $product->categories->first());
    }

    public function testGetProductsHasNoCategory()
    {
        $product = Product::factory()
            ->create();
        $this->assertCount(0, $product->categories);
    }


    public function testGetProductsParent()
    {
        $parentProduct = Product::factory()->create(['title' => 'Sample Parent Product']);
        /** @var Product $product */
        $product = Product::factory()->create(['parent_id' => $parentProduct->id]);
        $this->assertEquals('Sample Parent Product', $product->parent->title);
    }

    // @tahir please read the following and discuss why you insist defining a middle table ?
 // https://laravel.com/docs/8.x/eloquent-relationships#many-to-many
    public function testGetProductsTags()
    {
        $tag = Tag::factory()->create();

        $product = Product::factory()
            ->create();
        $product->tags()->attach($tag);
        $product->save();

        $this->assertEquals($tag->title, $product->tags()->first()->title);

        $product->tags()->detach($tag);
        $product->save();
        $this->assertNUll($product->tags()->first());

    }

    public function testImportProducts()
    {
        /** @var Product $product */
        $service = new ProductService();
        $result = $service->import((app_path()."/../tests/test-data/mot-products.xlsx"));
        $this->assertNull($result);
    }
    }


