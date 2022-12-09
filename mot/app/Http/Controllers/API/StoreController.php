<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\StoreOrder;
use App\Service\FilterOrderService;
use App\Service\FilterStoreOrderService;
use App\Service\StoreService;
use App\Http\Resources\StoreResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Service\FilterProductsService;
use Auth;

class StoreController extends BaseController
{
    /**
     * @param StoreService $storeService
     * @return \Illuminate\Http\Response
     */
    public function index(StoreService $storeService)
    {
//        $stores = StoreResource::collection($storeService->getAllStores(100));
        $stores = StoreResource::collection($storeService->getAllStores(20))->response()->getData(true);
        return $this->sendResponse($stores, __('Data loaded successfully'));
    }

    /**
     * @param $id
     * @param StoreService $storeService
     * @return \Illuminate\Http\Response
     */
    public function show($slug, StoreService $storeService, FilterProductsService $productService)
    {

//        $store = $storeService->getById($slug);

        $store = $storeService->getStore($slug);
        $storeData = [];

        $base_query = $productService->relations(['store', 'gallery', 'attributes','product_translates']);
        $base_query = $base_query->byStore($store->id);
        $base_query = $base_query->setActiveFilter();
        $products = $base_query->get();

        $storeData['id'] = $store->id;
        $storeData['name'] = $store->store_profile_translates ? $store->store_profile_translates->name : $store->name;
        $storeData['slug'] = $store->slug;
        $storeData['logo'] = $store->store_data->logo;
        $storeData['banner'] = $store->store_data->banner;
        $storeData['rating_count'] = $store->lifetimeRatingCount();
        $storeData['rating'] = $store->rating;
        $storeData['policy'] = $store->store_data->policies;
        $storeData['about_us'] = $store->store_data->description;
        $storeData['products'] = ProductResource::collection($products);
        $storeData['reviews'] = ReviewResource::collection($store->approved_reviews);
        $storeData['is_able_to_review'] = false;
        if (Auth('sanctum')->check()) {
            $customer = Auth('sanctum')->user();
            $storeData['is_able_to_review'] = $storeService->isAbleToReview($store, $customer);
        }
        $storeData['all_ratings'] = [
            $store->approved_reviews->where('rating', 1)->count(),
            $store->approved_reviews->where('rating', 2)->count(),
            $store->approved_reviews->where('rating', 3)->count(),
            $store->approved_reviews->where('rating', 4)->count(),
            $store->approved_reviews->where('rating', 5)->count(),
        ];

        return $this->sendResponse($storeData, __('Data loaded successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeReview(Request $request)
    {
        if (!Auth('sanctum')->check()) {
            $this->sendError(__('User not found'), []);
        }
        $validator = Validator::make($request->all(), [
            'store_id' => 'numeric|required',
//            'store_order_id' => 'numeric|required',
            'rating' => 'required|numeric',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        try {
            $storeService = new StoreService();
            $customer = Auth('sanctum')->user();
            $storeOrder = StoreOrder::with('order')->wherehas('order', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })->where('store_id', $request->store_id)->first();

            $data = [
                'store_id' => $request->store_id,
                'language_id' => 1,
                'customer_id' => Auth('sanctum')->user()->id,
                'store_order_id' => $storeOrder != null ? $storeOrder->id : StoreOrder::first()->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_approved' => 0,
            ];
            $storeReview = $storeService->createFeedback($data);
        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }

        return $this->sendResponse($storeReview, __('Your feedback has been submitted successfully !'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeQuestion(Request $request)
    {
        try {
            $storeService = new StoreService();
            $customer_id = null;
            if (Auth('sanctum')->check()) {
                $customer_id = Auth('sanctum')->user()->id;
            }
            $storeService->createQuestion($request->all() + ['customer_id' => $customer_id]);
        } catch (\Exception $exc) {
            return $this->sendError(__($exc->getMessage()));
        }
        return $this->sendResponse([], __('Your question has been sent to seller!'));
    }
}
