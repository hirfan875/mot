<?php

namespace App\Service;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponService
{
    /**
     * create new coupon
     *
     * @param array $request
     * @param bool $is_admin
     * @return Coupon
     */
    public function create(array $request, $is_admin = false): Coupon
    {
        $coupon = new Coupon();
        $coupon->is_admin = $is_admin;

        $this->saveCouponFromRequest($coupon, $request);
        $coupon->image = Media::handle($request, 'image', $coupon);
        $coupon->save();

        // save products
        if (isset($request['products']) && !empty($request['products']) && $request['applies_to'] == 2) {
            $coupon->products()->sync($request['products']);
            return $coupon;
        }

        // save categories
        if (isset($request['categories']) && !empty($request['categories']) && $request['applies_to'] == 3) {
            $coupon->categories()->sync($request['categories']);
        }

        return $coupon;
    }

    /**
     * update coupon
     *
     * @param Coupon $coupon
     * @param array $request
     * @return Coupon
     */
    public function update(Coupon $coupon, array $request): Coupon
    {
        $this->saveCouponFromRequest($coupon, $request);
        $coupon->image = Media::handle($request, 'image', $coupon);
        $coupon->save();

        // save products
        if (isset($request['products']) && !empty($request['products']) && $request['applies_to'] == 2) {
            $coupon->products()->sync($request['products']);
            return $coupon;
        }

        // save categories
        if (isset($request['categories']) && !empty($request['categories']) && $request['applies_to'] == 3) {
            $coupon->categories()->sync($request['categories']);
        }

        return $coupon;
    }

    /**
     * save coupon from request
     *
     * @param Coupon $coupon
     * @param array $request
     * @return void
     */
    private function saveCouponFromRequest(Coupon $coupon, array $request)
    {
        $coupon->title = $request['title'];

        if($request['coupon_code']){
            $coupon->coupon_code =$request['coupon_code'];
        }

        $coupon->store_id = $request['store'];
        $coupon->start_date = Carbon::parse($request['start_date'])->toDateTime();
        $coupon->end_date = Carbon::parse($request['end_date'])->toDateTime();
        $coupon->type = $request['type'];
        $coupon->discount = isset($request['discount']) ? $request['discount'] : null;
        $coupon->get_limit = isset($request['get_limit']) ? $request['get_limit'] : null;
        $coupon->buy_limit = isset($request['buy_limit']) ? $request['buy_limit'] : null;
        $coupon->usage_limit = $request['usage_limit'];
        $coupon->total_limit = $request['usage_limit'] == 2 ? $request['limit'] : null;
        $coupon->per_user_limit = $request['usage_limit'] == 2 ? $request['per_user_limit'] : null;
        $coupon->applies_to = $request['applies_to'];
        $coupon->sub_total = $request['sub_total'];
    }

    /**
     * @param $productId
     * @return Coupon[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getByProductId($product)
    {
//        $today_date = now()->toDateTimeString();
        $categoryIds = $product->categories->pluck('id')->toArray();

        $baseCoupons = Coupon::where('status', true)->where(function ($query) use ($product, $categoryIds) {
            $query->orWhereHas('products', function ($productsQuery) use ($product) {
                return $productsQuery->where('product_id', $product->id);
            })->orWhere('store_id', $product->store_id)->orWhereHas('categories', function ($categoryQuery) use ($categoryIds) {
                return $categoryQuery->whereIn('category_id', $categoryIds);
            });
        });

        $coupons = $baseCoupons->get();

        return $coupons;
    }

    public function getActive()
    {
        $coupons = Coupon::where('status', true)->get();
//        $coupons = Coupon::where('status', true)->whereNotNull('coupon_code')->get();
        return $coupons;
    }
}
