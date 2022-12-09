<?php

namespace Tests\Unit\Service;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Store;
use App\Models\TrendingProduct;
use App\Models\Wishlist;
use App\Service\FilterProductsService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class FilterProductsServiceTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        Product::query()->delete();
        Product::factory()->count(10)->create(['type'=>Product::TYPE_SIMPLE]);
        Product::factory()->count(10)->create(['type'=>Product::TYPE_BUNDLE]);
        Product::factory()->count(10)->create(['type'=>Product::TYPE_VARIABLE]);
        $filter = new FilterProductsService();
        $this->assertEquals(30, $filter->count());
    }

    /**
     * Variation products should not show up in filter results, but their parents should
     */
    public function testVariationTest()
    {
        $this->markTestIncomplete('This feature is not completed yet');
        Product::query()->delete();
        Product::factory()->count(10)->create(['type'=>Product::TYPE_VARIATION]);
        $filter = new FilterProductsService();
        $this->assertEquals(0, $filter->count());
    }

    public function testFilterProductsByKeyword()
    {
        /** @var FilterProductsService $filter */
        $filter = new FilterProductsService();
        $filter->byKeyword('square house');
        $this->assertCount(1, $filter->paginate());
    }

    public function testFilterProductsByBrands()
    {
        $brand = Brand::factory()->create();
        $product = Product::factory()->create(['brand_id'=> $brand->id]);

        /** @var FilterProductsService $filter */
        $filter = new FilterProductsService();
        $filter->byBrand($brand->id);
        $products = $filter->paginate();
        $this->assertCount(1, $products);
        $this->assertEquals($product->id, $products->items()[0]->id);
    }

    public function testFilterProductsByMetaKeyword()
    {
        /** @var FilterProductsService $filter */
        $filter = new FilterProductsService();
        $filter = $filter->byKeyword('DSLR', true);
        $products = $filter->paginate();
        $this->assertCount(1, $products);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testActiveProductTest()
    {
        Product::query()->delete();
        $product = Product::factory()->create(['status' => 0]);

        $filter = new FilterProductsService();
        $filter->setActiveFilter();
        $this->assertEquals(0, $filter->count());
    }

    public function testFilterProductsByMetaKeywordWithComma()
    {
        /** @var FilterProductsService $filter */
        $filter = new FilterProductsService();
        $filter = $filter->byKeyword('canon', true);
        $products = $filter->paginate();
        $this->assertCount(1, $products);
    }

    public function testFilterProductsByMultipleKW()
    {
        /** @var FilterProductsService $filter */
        $filter = new FilterProductsService();
        $filter = $filter->byKeyword('digital-camera, camera', true);
        $products = $filter->paginate();
        $this->assertCount(1, $products);
    }
    public function testGetProductsByCategory()
    {
        $product = Product::factory()
            ->hasAttached(Category::factory())
            ->create();
        $this->assertCount(1, $product->categories);
        $this->assertInstanceOf(Category::class, $product->categories->first());


        $filter = new FilterProductsService();
        $filter = $filter->byCategory($product->categories->first()->id);

        $products = $filter->paginate();
        $this->assertEquals($product->id, $products->items()[0]->id);
    }

    public function testGetRelevantBrandsContextCategory()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $products = Product::factory(10)
            ->create(['brand_id' => $brand->id])->each(function (Product $product) use ($category){
                $product->categories()->attach($category);
            });
        $filter = new FilterProductsService();
        $brands = $filter
            ->setContext(FilterProductsService::LIST_MODE_CATEGORY)
            ->byCategory($products[0]->categories[0]->id)
            ->getBrands();
        $this->assertEquals($brand->id, $brands[0]->id);
        $this->assertCount(1, $brands);
    }

    public function testGetProductsByStore()
    {
        $store = Store::factory()->create();
        $products = Product::factory(20)
            ->create(['store_id' => $store->id]);

        $filter = new FilterProductsService();
        $filter = $filter->byStore($store->id);
        $products = $filter->get();
        $this->assertCount(20, $products);
    }

    public function testFilterProductsByDisabledStore()
    {
        $store = Store::factory()->create(['status' => false]);
        $products = Product::factory(20)
            ->create(['store_id' => $store->id]);

        $filter = new FilterProductsService();
        $products = $filter
            ->setExcludeInactiveStore(true)
            ->byStore($store->id)
            ->get();
        $this->assertCount(0, $products);
    }


    public function testFilterProductsByPriceRange()
    {
        $store = Store::factory()->create();
        $products = Product::factory()
            ->create(['promo_price' => 1 , 'store_id' => $store->id]);

        $filter = new FilterProductsService();
        $products = $filter
            ->byPriceRange(0.5,1.5)
            ->get();
        $this->assertCount(1, $products);
    }

    public function testFilterProductsByAttribute()
    {
        $sizeAttribute = Attribute::factory()->create(['title' => 'Size' , 'parent_id'=> null]);
        $sizeAttributeOption = Attribute::factory()->create(['title' => 'Large' , 'parent_id'=> $sizeAttribute->id]);

        $productWithAttribute = Product::factory()->create();
        $p  = ProductAttribute::factory()->create([
            'product_id' => $productWithAttribute->id,
            'option_id' => $sizeAttributeOption->id,
        ]);
        $filter = new FilterProductsService();
        $products = $filter
            ->byAttributes([$sizeAttributeOption->id])
        ->get();
        $this->assertCount(1, $products);
    }

    public function testFilterProductVariationByAttribute()
    {
        $sizeAttribute = Attribute::factory()->create(['title' => 'Size' , 'parent_id'=> null]);
        $sizeAttributeOption = Attribute::factory()->create(['title' => 'Large' , 'parent_id'=> $sizeAttribute->id]);

        $parentProduct = Product::factory()->create();
        $productWithAttribute = Product::factory()->create(['parent_id' => $parentProduct->id]);
        $p = ProductAttribute::factory()->create([
            'product_id' => $parentProduct->id,
            'variation_id' => $productWithAttribute->id,
            'attribute_id' => $sizeAttribute->id,
            'option_id' => $sizeAttributeOption->id,
        ]);
        $filter = new FilterProductsService();
        $products = $filter
            ->byAttributes([$sizeAttributeOption->id])
            ->get();
        $this->assertCount(1, $products);
    }


    public function testGetTrendingProducts()
    {
        /** @var TrendingProduct $trendingProductSection */
        $trendingProductSection = TrendingProduct::factory()->create();
        /** @var Product $product */
        $product = Product::factory()->create();
        $product->categories()->attach($trendingProductSection->category_id);
        $product->tags()->attach($trendingProductSection->tag_id);
        $product->save();

        $filter = new FilterProductsService();
        $products = $filter
            ->byTrendingProductSection($trendingProductSection)
            ->get();
        $this->assertCount(1, $products);
    }

    public function testFilterProductsHaveWishList()
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['title' => 'ProductInWishlist']);
        $wishlist = Wishlist::factory()->create([
            'product_id' => $product->id,
            'customer_id' => $customer->id
        ]);

        /** @var FilterProductsService $filter */
        $filter = new FilterProductsService();
        $filter->setCustomerId($customer->id);
        $filter->byIds([$product->id]);
        $products = $filter->paginate();
        /** @var Product $actualProduct */
        $actualProduct = $products->items()[0];
        $this->assertTrue($actualProduct->wishlist);
    }
}
