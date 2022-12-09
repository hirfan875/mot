<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Select2ProductResource;
use App\Service\FilterProductsService;
use App\Service\MotFeeService;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * get all products
     *
     * @param Request $request
     * @param FilterProductsService $filterProductsService
     * @return \Illuminate\Http\Response
     */
    public function getProductsForSelect2(Request $request, FilterProductsService $filterProductsService)
    {
        if (!$request->has('keyword') || empty($request->keyword)) {
            return;
        }

        $limit = 15;
        if ($request->has('limit')) {
            $limit = $request->limit;
        }

        // set query order
        $order_by = 'title';
        $order = 'asc';
        if ($request->has('order_by')) {
            $order_by = $request->order_by;
        }
        if ($request->has('order')) {
            $order = $request->order;
        }

        // only include store products
        $sellerStaff = auth()->guard('seller')->user();
        if ($sellerStaff) {
            $filterProductsService->byStore($sellerStaff->store_id);
        }

        // exclude free delivery products
        if (isset($request->free_delivery)) {
            $filterProductsService->exludeFreeDeliveryProducts();
        }

        $products = $filterProductsService->setActiveFilter()
            ->select(['id', 'title', 'sku'])
            ->byKeywordOrSkuLike($request->keyword)
            ->sortBy([$order_by => $order])
            ->take($limit)
            ->get();

        return response()->json(['results' => Select2ProductResource::collection($products)]);
    }

    /**
     * get mot commission
     *
     * @param Request $request
     * @param MotFeeService $motFeeService
     * @return \Illuminate\Http\Response
     */
    public function getMotCommission(Request $request, MotFeeService $motFeeService)
    {
        if($request->store_id){
            $commission = $motFeeService->getCommission($request->store_id, $request->categories);
            return response()->json(['commission' => $commission]);
        }
        $response = [
            'success'   => false,
            'message'   => __("Kindly provide store information"),
            'store_id'      => null
        ];
        return response()->json($response, 500);
    }
}
