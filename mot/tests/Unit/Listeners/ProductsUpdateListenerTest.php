<?php

namespace Tests\Unit\Listeners;

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

use App\Listeners\UpdatePriceTable;


class ProductsUpdateListenerTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateProductTest()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $event  = new ProductPriceUpdate($product);
        $listener = new UpdatePriceTable();
        $listener->handle($event);

        $this->assertEquals($product->promo_price, $product->discounted_price);
    }

}
