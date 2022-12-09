<?php

namespace Tests\Unit\Service;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Service\ProductService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Events\ProductPriceUpdate;

class ProductsServiceTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCrateProductTest()
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $store = Store::factory()->create();
        $service = new ProductService();
        $product = $service->create([
            'title' => $this->faker->sentence,
            'brand' => $brand->id,
            'store' => $store->id,
            'sku' => 'ásd-1213',
            'price' => 2000,
            'categories' =>[ $category->id],
            'discount' => null,
            'discount_type' => null,
            'bundle_products' => null,
            'attributes' => null,
            'variations' => null,
            'type' => Product::TYPE_SIMPLE,
            'stock' => 10,
            'free_delivery' => false,
            'delivery_fee' => 100,
            'data' => $this->faker->paragraph,
            'meta_title' => $this->faker->sentence,
            'meta_desc' => $this->faker->sentence,
            'meta_keyword' => $this->faker->sentence,
        ]);
        $this->assertIsObject($product);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCrateProductUpdatePromoPriceTest()
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $store = Store::factory()->create();
        $service = new ProductService();
        $product = $service->create([
            'title' => $this->faker->sentence,
            'brand' => $brand->id,
            'store' => $store->id,
            'sku' => 'ásd-1213',
            'price' => 2000,
            'categories' =>[ $category->id],
            'discount' => null,
            'discount_type' => null,
            'bundle_products' => null,
            'attributes' => null,
            'variations' => null,
            'type' => Product::TYPE_SIMPLE,
            'stock' => 10,
            'free_delivery' => false,
            'delivery_fee' => 100,
            'data' => $this->faker->paragraph,
            'meta_title' => $this->faker->sentence,
            'meta_desc' => $this->faker->sentence,
            'meta_keyword' => $this->faker->sentence,
        ]);

        $this->assertEquals(2000,$product->promo_price);
    }

    /**
     * test if an event is fired
     * @throws \Exception
     */
    public function testProductUpdateWithDailyDealUpdatePromoPriceTest()
    {
        $this->expectsEvents(ProductPriceUpdate::class);
        $product = Product::factory()->create();
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $store = Store::factory()->create();
        $staff = StoreStaff::factory()->create(['store_id' => $store->id]);
        $service = new ProductService();
        $product = $service->update($product, [
            'title' => $this->faker->sentence,
            'brand' => $brand->id,
            'store' => $store->id,
            'sku' => 'ásd-1213',
            'price' => 2000,
            'categories' =>[ $category->id],
            'discount' => null,
            'discount_type' => null,
            'bundle_products' => null,
            'attributes' => null,
            'variations' => null,
            'type' => Product::TYPE_SIMPLE,
            'stock' => 10,
            'free_delivery' => false,
            'delivery_fee' => 100,
            'data' => $this->faker->paragraph,
            'meta_title' => $this->faker->sentence,
            'meta_desc' => $this->faker->sentence,
            'meta_keyword' => $this->faker->sentence,
        ], $staff);
    }
}
