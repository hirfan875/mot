<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Extensions\Response;
use App\Models\StoreReview;
use App\Service\FilterProductsService;
use App\Service\StoreService;
use App\Service\FilterStoreOrderService;
use App\Models\Customer;
use App\Models\StoreOrder;
use App\Models\Order;
use App\Service\OrderService;
use Auth;

class StoreController extends Controller
{
    public function index(StoreService $request)
    {
        $stores = $request->getAllStores(15);
        $data = [
            'stores' => $stores
        ];

        return view('web.store.index', $data);
    }

    public function show(Request $request, $slug, FilterProductsService $productService)
    {
        try {
            $storeOrders=0;
            if(Auth()->guard('customer')->user() != null){
                $customer = Customer::findOrFail(Auth()->guard('customer')->user()->id);
                if($customer){
                    $orderService = new FilterStoreOrderService();
                    $orderService->byCustomer($customer->id);
                    $orderService->byArchive(StoreOrder::NOTARCHIVED);
                    // todo replace get to paginate
                    $storeOrders = $orderService->relations(['order.customer', 'order.store_orders'])->latest()->get()->count();
                }
            }

            $storeData = Store::with('store_data')->where('status', true)->where('slug', $slug)->first();

            $meta_title = '';
            $meta_description = '';
            $meta_keyword = '';

            if($storeData !=null){
            $meta_title = isset($storeData->store_profile_translates->meta_title) ? $storeData->store_profile_translates->meta_title : $storeData->store_data->meta_title;
            $meta_description = isset($storeData->store_profile_translates->meta_desc) ? $storeData->store_profile_translates->meta_desc : $storeData->store_data->meta_desc;
            $meta_keyword = isset($storeData->store_profile_translates->meta_keyword) ? $storeData->store_profile_translates->meta_keyword : $storeData->store_data->meta_keyword;
            }
            
            if($meta_title == '') {
                $meta_title = isset($storeData->name) ? $storeData->name : '';
            }
            if($meta_description == '') {
                if(isset($storeData->store_data)){
                $meta_description = isset($storeData->store_profile_translates->description) ? $storeData->store_profile_translates->description : $storeData->store_data->description;
                }
            }
            if($meta_keyword == '') {
                $meta_keyword = isset($storeData->name) ? $storeData->name : '';
            }

            $storeService = new StoreService();
            $store = $storeService->getStore($slug);
            if (!$store) {
                throw new ModelNotFoundException(__('Store Not Found'));
            }

            $store_reviews = StoreReview::where('store_id' , $store->id)->where('is_approved', true)->where('language_id',getLocaleId(app()->getLocale()))->get()->groupBy('rating');
            $base_query = $productService->relations(['store', 'gallery', 'attributes','product_translates']);
            $base_query = $base_query->byStore($store->id);
            $base_query = $base_query->setActiveFilter();
            $per_page   = isset($request->per_page) ? $request->per_page : 16;
            $products = $base_query->paginate($per_page);
        } catch (ModelNotFoundException $exc) {
            return Response::error('not-found', __($exc->getMessage()), $exc, $request, 404);
        } catch (\Exception $exc) {
            return Response::error('web.store.detail', __($exc->getMessage()), $exc, $request);
        }
        return Response::success('web.store.detail', [
            'store' => $store,
            'per_page' => $per_page,
            'products'  => $products,
            'store_reviews'  =>  $store_reviews,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
            'meta_keyword' => $meta_keyword,
            'storeOrders' => $storeOrders,
        ], $request);
    }


    public function storeQuestion(Request $request)
    {
        try {
            $storeService  = new StoreService();
            $customer_id = null;
            if (Auth::guard('customer')->check()) {
                $customer_id = Auth::guard('customer')->user()->id;
            }
            $storeQuestion = $storeService->createQuestion($request->all() + ['customer_id' => $customer_id]);
        } catch (\Exception $exc) {
            return Response::redirect(route('store-question'), $request, ['message'=> __($exc->getMessage())]);
        }
        return redirect()->back()->with('success', __('Your question has been sent successfully !'));
    }
}
