<?php

namespace Tests\Unit\Service;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\CustomerAddress;
use App\Models\DailyDeal;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreOrder;
use App\Service\DailyDealService;
use App\Service\MoTCartService;
use App\Service\OrderService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DailDealServiceTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     *
     * @return void
     */
    public function testGetDailyDealForHomepage()
    {
        DailyDeal::truncate();
        /** @var Product $product */
        $product = Product::factory()->create(['stock' => 10]);
        /** @var DailyDeal $deal */
        $deal = DailyDeal::factory()->create(['product_id' => $product->id]);
        $dealService = new DailyDealService();
        $dealInEffect  = $dealService->getDealsForHomePage();
        $this->assertEquals($deal->id, $dealInEffect->first()->id);
    }
    /**
     *
     * @return void
     */
    public function testGetDailyDealForProduct()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        /** @var DailyDeal $deal */
        $deal = DailyDeal::factory()->create(['product_id' => $product->id]);
        $dealService = new DailyDealService();
        $dealInEffect  = $dealService->getDealsForProduct($product);
        $this->assertEquals($deal->id, $dealInEffect->first()->id);
    }

    /**
     *
     * @return void
     */
    public function testGetDailyDealForProductPickHightestDiscount()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        /** @var DailyDeal $deal */
        $deal = DailyDeal::factory()->create(['product_id' => $product->id , 'discount' => 50]);
        $deal = DailyDeal::factory()->create(['product_id' => $product->id , 'discount' => 51]);
        $dealService = new DailyDealService();
        $dealInEffect  = $dealService->getDealsForProduct($product);
        $this->assertEquals($deal->id, $dealInEffect->first()->id);
    }
    /**
     *
     * @return void
     */
    public function testGetDailyDealForProductIgnoresExpired()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        /** @var DailyDeal $deal */
        $deal = DailyDeal::factory()->create(['ending_at' => Carbon::yesterday(),'product_id' => $product->id]);
        $dealService = new DailyDealService();
        $dealInEffect  = $dealService->getDealsForProduct($product);
        $this->assertEquals(0, $dealInEffect->count());
    }
    /**
     *
     * @return void
     */
    public function testGetDailyDealForProductIgnoresInActive()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        /** @var DailyDeal $deal */
        $deal = DailyDeal::factory()->create(['status' =>false,'product_id' => $product->id]);
        $dealService = new DailyDealService();
        $dealInEffect  = $dealService->getDealsForProduct($product);
        $this->assertEquals(0, $dealInEffect->count());
    }

    /**
     *
     * @return void
     */
    public function testGetDailyDealForProductIgnoresNonApproved()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        /** @var DailyDeal $deal */
        $deal = DailyDeal::factory()->create(['is_approved' =>false,'product_id' => $product->id]);
        $dealService = new DailyDealService();
        $dealInEffect  = $dealService->getDealsForProduct($product);
        $this->assertEquals(0, $dealInEffect->count());
    }

}
