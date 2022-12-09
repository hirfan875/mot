<?php

namespace App\Service;

use App\Events\ProductPriceUpdate;
use App\Models\FlashDeal;
use App\Models\StoreStaff;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;

class FlashDealService
{
    /**
     * create new deal
     *
     * @param array $request
     * @return FlashDeal
     */
    public function create(array $request, StoreStaff $seller): FlashDeal
    {
        $deal = new FlashDeal();

        $deal->store_id = $seller->store_id;
        $deal->created_by = $seller->id;
        $deal->image = Media::handle($request, 'image', $deal, 'deal');

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
     * @param FlashDeal $deal
     * @param array $request
     * @return FlashDeal
     */
    public function update(FlashDeal $deal, array $request): FlashDeal
    {
        $this->saveDealFromRequest($deal, $request);
        $deal->image = Media::handle($request, 'image', $deal, 'deal');
        $deal->save();

        Media::saveCropImage($request, 'deal_home', $deal->image);

        // dispatch events
        ProductPriceUpdate::dispatch($deal->product);

        return $deal;
    }

    /**
     * set deal data from request
     *
     * @param FlashDeal $deal
     * @param array $request
     * @return void
     */
    private function saveDealFromRequest(FlashDeal $deal, array $request)
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
        return FlashDeal::query()
            ->whereIsApproved(true)
            ->whereStatus(true)
            ->where('starting_at', '<=', now())
//            ->where('ending_at', '>=', now())
            ->with('product')
            ->wherehas('product', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->whereHas('store', function ($query) {
                $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
            })
            ->orderBy('sort_order','asc')
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
        return FlashDeal::query()
            ->whereIsApproved(true)
            ->whereStatus(true)
            ->where('starting_at', '<=', now())
//            ->where('ending_at', '>=', now())
            ->with('product')
            ->wherehas('product', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->whereHas('store', function ($query) {
                $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
            })
            ->orderBy('sort_order','asc')
            ->take($take)
            ->get();
    }
}
