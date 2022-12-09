<?php

namespace App\Listeners;

use App\Events\ProductPriceUpdate;
use App\Models\DailyDeal;
use App\Models\FlashDeal;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Service\DailyDealService;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Cart;
use App\Models\CartProduct;
use Monolog\Logger;
use Illuminate\Support\Carbon;

class UpdatePriceTable implements ShouldQueue
{
    /** @var float */
    protected $price;

    /** @var string */
    protected $discount_source;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'update-price-queue';

    /**
     * Handle the event.
     *
     * @param  ProductPriceUpdate  $event
     * @return void
     */
    public function handle(ProductPriceUpdate $event)
    {
        
        
        /** @var Logger $logger */
        $logger = getLogger('update-price-queue');
        $logger->info('Updating Prices of ' . $event->product->title);
        $this->price = $event->product->discounted_price;
        $this->discount_source = $event->product;

        $logger->info('Current Price of ' . $event->product->title . ' is ' , [$this->price]);

        $daily_deal = $this->getDailyDealDiscount($event->product);
        if ($daily_deal) {
            $daily_deal_discount =  $daily_deal->discounted_price;
            $logger->info('Daily Deal Price of ' . $event->product->title . ' is ' , [$daily_deal_discount]);

            if ($daily_deal_discount < $this->price) {
                $this->price = $daily_deal_discount;
                $this->discount_source = $daily_deal;
            }
        }

        $flash_deal = $this->getFlashDealDiscount($event->product);
        if ($flash_deal) {
            $flash_deal_discount = $flash_deal->discounted_price;
            $logger->info('Flash Deal Price of ' . $event->product->title . ' is ' , [$flash_deal_discount]);
            if ($flash_deal_discount < $this->price) {
                $this->price = $flash_deal_discount;
                $this->discount_source = $flash_deal;
            }
        }

        $logger->info('Final Price of ' . $event->product->title . ' is ' , [$this->price]);
        $this->updateProductPrice($event->product);

        $cart_price = $this->updateCartPrice($event->product);
        if ($cart_price) {
            $logger->info('Updated Price on Cart of ' . $event->product->title . ' is ' , [$event->product->promo_price]);
        }
        
    }

    /**
     * get daily deals related to that product
     *
     * @param Product $product
     * @return DailyDeal
     */
    protected function getDailyDealDiscount(Product $product): ?DailyDeal
    {
        $dailyDealService = new DailyDealService();
        return $dailyDealService->getDealsForProduct($product)->first();
    }

    /**
     * get flash deals related to that product
     *
     * @param Product $product
     * @return FlashDeal
     */
    protected function getFlashDealDiscount(Product $product): ?FlashDeal
    {
        return FlashDeal::whereProductId($product->id)->whereStatus(true)->whereIsApproved(true)
                ->whereDate('starting_at','<=', Carbon::now())
            ->whereDate('ending_at','>=', Carbon::now())
            ->first();
    }

    /**
     * update product price
     *
     * @param Product $product
     * @return void
     */
    protected function updateProductPrice(Product $product)
    {
        $source = $this->getDiscountSource($this->discount_source);
        
        ProductPrice::updateOrCreate(
            ['product_id' => $product->id],
            ['price' => $this->price, 'discount_source' => $source]
        );
        $product->promo_price = $this->price;
        $product->promo_source_type = $source;
        $product->promo_source_id = $this->discount_source->id;
        $product->save();
    }
    
    public function updateCartPrice(Product $product) {
        
        return CartProduct::where('product_id', $product->id)->update(
            [
                'unit_price' => $product->promo_price,
                'message' => sprintf(__("Price change of %s is %s"),$product->title,$product->promo_price)
            ]
        );
    }

    /**
     * @param $discountSource
     * @return false|string
     */
    private function getDiscountSource($discountSource)
    {
        return get_class($discountSource);
    }
}
