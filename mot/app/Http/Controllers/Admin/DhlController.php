<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Service\FilterProductsService;
use App\Service\DhlService;
use App\Models\StoreOrder;
use App\Service\OrderService;
use App\Models\Customer;
use App\Models\Order;
use App\Models\ShipmentResponse;

class DhlController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shipmentRequest(Request $request, StoreOrder $storeOrder)
    {
       try {
            $storeOrder->load(['order.customer', 'order.customerAddresses', 'seller', 'order_items.product']);
            $dhlService = new DhlService;
            $dhlService->getShipmentRequest($request->toArray(),$storeOrder);
            return back()->with('success', __('Request sent successfully.'));

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pickUpRequest(Request $request, StoreOrder $storeOrder)
    {
       try {
            $storeOrder->load(['order.customer', 'order.customerAddresses', 'seller']);
            $dhlService = new DhlService;
            $dhlService->getPickUpRequest($request->toArray(),$storeOrder);
            return back()->with('success', __('Request sent successfully.'));
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rateRequestForValidateStoreAddress($storeOrder)
    {
        
       try {
            $dhlService = new DhlService;
            $result = $dhlService->getRateRequestForValidateStoreAddress($storeOrder);
            return back()->with('success', __('Request sent successfully.'));

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
}