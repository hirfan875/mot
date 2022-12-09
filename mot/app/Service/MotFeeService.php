<?php

namespace App\Service;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;

class MotFeeService
{
    /**
     * get mot fee commission by store or category
     *
     * @param int $store_id
     * @param array $categories
     * @return int|void
     */
    public function getCommission(int $store_id, array $categories = [])
    {
        if (!$store_id) {
            return;
        }

        $store = Store::find($store_id);
        if ($store && $store->commission) {
            return $store->commission;
        }

        if (empty($categories)) {
            return;
        }

        $getHighestCommissionCategory = Category::whereIn('id', $categories)->orderBy('commission', 'desc')->first();
        if (!$getHighestCommissionCategory || !$getHighestCommissionCategory->commission) {
            return;
        }

        return $getHighestCommissionCategory->commission;
    }

    /**
     * get product commission
     *
     * @param Product $product
     * @return int|null
     */
    public function getProductCommission(Product $product)
    {
        $categories = $product->categories->pluck('id')->toArray();
        if($product->store_id){
            return $this->getCommission($product->store_id, $categories);
        }
        $response = [
            'success'   => false,
            'message'   => __("Kindly provide store information"),
            'store_id'      => null
        ];
        return response()->json($response, 500);
    }

    /**
     * get mot commission amount
     *
     * @param float $price
     * @param int|null $commission
     * @return float
     */
    public function getCommissionAmount(float $price, int $commission = null)
    {
        if (!$commission) {
            return;
        }

        return $price * ($commission / 100);
    }
}
