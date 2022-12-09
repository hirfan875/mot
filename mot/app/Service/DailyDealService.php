<?php

namespace App\Service;

use App\Events\ProductPriceUpdate;
use App\Models\DailyDeal;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreStaff;
use Illuminate\Database\Eloquent\Collection;

class DailyDealService
{
    /**
     * create new deal
     *
     * @param array $request
     * @return DailyDeal
     */
    public function create(array $request, StoreStaff $seller): DailyDeal
    {
        $deal = new DailyDeal();

        $deal->store_id = $seller->store_id;
        $deal->created_by = $seller->id;
        $deal->image = Media::handle($request, 'image',$deal);
        $deal->expired = false;

        $this->saveDealFromRequest($deal, $request);
        $deal->save();

        Media::saveCropImage($request, 'deal_home', $deal->image);

        // dispatch events
        ProductPriceUpdate::dispatch($deal->product);

        return $deal;
    }

    /**
     * update deal
     *
     * @param DailyDeal $deal
     * @param array $request
     * @return DailyDeal
     */
    public function update(DailyDeal $deal, array $request): DailyDeal
    {
        $this->saveDealFromRequest($deal, $request);
        $deal->image = Media::handle($request, 'image', $deal);
        $deal->save();

        Media::saveCropImage($request, 'deal_home', $deal->image);

        // dispatch events
        ProductPriceUpdate::dispatch($deal->product);

        return $deal;
    }

    /**
     * set deal data from request
     *
     * @param DailyDeal $deal
     * @param array $request
     * @return void
     */
    private function saveDealFromRequest(DailyDeal $deal, array $request)
    {
        $start_date = $request['start_date'] . " " . $request['start_time'];
        $end_date = $request['end_date'] . " " . $request['end_time'];

        $deal->product_id = $request['product'];
        $deal->discount = $request['discount'];
        $deal->starting_at = $start_date;
        $deal->ending_at = $end_date;
    }

    /**
     * get all active deals
     *
     * @return Collection
     */
    public function getAllDeals($perPage = null)
    {
        return DailyDeal::query()
            ->whereIsApproved(true)
            ->whereStatus(true)
            ->whereExpired(false)
            ->where('starting_at', '<=', now())
            ->where('ending_at', '>=', now())
            ->with('product')
            ->wherehas('product', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->whereHas('store', function ($query) {
                $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
            })
            ->paginate($perPage);
    }

    /**
     * get active deals for home page
     *
     * @param int $take
     * @return Collection
     */
    public function getDealsForHomePage(int $take = 8): Collection
    {

        return DailyDeal::query()
            ->whereIsApproved(true)
            ->whereStatus(true)
            ->where('starting_at', '<=', now())
            ->where('ending_at', '>=', now())
            ->with('product','product.product_translates')
            ->wherehas('product', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->whereHas('store', function ($query) {
                $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
            })
            ->take($take)
            ->get();
    }

    /**
     * get active deals for a specific Product that is in effect
     *
     * @param Product $product
     * @return Collection
     */
    public function getDealsForProduct(Product $product): Collection
    {
        return DailyDeal::query()
            ->whereIsApproved(true)
            ->whereStatus(true)
            ->whereProductId($product->id)
            ->where('starting_at', '<=', now())
            ->where('ending_at', '>=', now())
            ->whereHas('store', function ($query) {
                $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
            })
            ->orderByDesc('discount')
            ->get();
    }
}
