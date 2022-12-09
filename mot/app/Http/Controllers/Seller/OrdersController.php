<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreOrder;
use App\Service\FilterProductsService;
use App\Service\FilterStoreOrderService;
use App\Service\OrderService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FilterStoreOrderService $filterStoreOrderService, FilterProductsService $filterProductsService)
    {
        $user = auth()->guard('seller')->user();
        $filterStoreOrderService->byStore($user->store_id)->active();

        if ($request->has('product') && !empty($request->product)) {
            $filterStoreOrderService->byProduct($request->product);
        }

        $records = $filterStoreOrderService->relations(['order.customer', 'order.currency', 'order_items'])->latest()->get();
        $products = $filterProductsService->setActiveFilter()->byStore($user->store_id)->sortBy(['title' => 'asc'])->get();

        return view('seller.orders.index', [
            'title' => __('Orders'),
            'records' => $records,
            'products' => $products,
            'request_params' => $request->toArray()
        ]);
    }

    /**
     * Show the detail for specified resource.
     *
     * @param StoreOrder $order
     * @return \Illuminate\Http\Response
     */
    public function detail(StoreOrder $order)
    {
        $order->load(['order.customer', 'order.currency', 'order_items.product']);
        $status_buttons = $order->getPossibleStatusButtonSeller();

        return view('seller.orders.detail', [
            'title' => __('Order Detail'),
            'section_title' => __('Orders'),
            'row' => $order,
            'status_buttons' => $status_buttons
        ]);
    }
    
    
    public function pendingOrders(Request $request, FilterStoreOrderService $filterStoreOrderService, FilterProductsService $filterProductsService)
    {
        $user = auth()->guard('seller')->user();
        $filterStoreOrderService->byStore($user->store_id)->active();

        if ($request->has('product') && !empty($request->product)) {
            $filterStoreOrderService->byProduct($request->product);
        }
        $filterStoreOrderService->byStatus([StoreOrder::PAID_ID, StoreOrder::CONFIRMED_ID]);
        $records = $filterStoreOrderService->relations(['order.customer', 'order.currency', 'order_items'])->latest()->get();
        $products = $filterProductsService->setActiveFilter()->byStore($user->store_id)->sortBy(['title' => 'asc'])->get();

        return view('seller.orders.index', [
            'title' => __('Orders'),
            'records' => $records,
            'products' => $products,
            'request_params' => $request->toArray()
        ]);
    }

    /**
     * Show the detail for specified resource.
     *
     * @param StoreOrder $order
     * @return \Illuminate\Http\Response
     */
    public function pendingOrdersDetail(StoreOrder $order)
    {
        $order->load(['order.customer', 'order.currency', 'order_items.product']);
        $status_buttons = $order->getPossibleStatusButtonSeller();

        return view('seller.orders.detail', [
            'title' => __('Order Detail'),
            'section_title' => __('Orders'),
            'row' => $order,
            'status_buttons' => $status_buttons
        ]);
    }

    /**
     * update order status
     *
     * @param StoreOrder $order
     * @param int $status
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(StoreOrder $order, int $status)
    {
        try {
//            $old_status = $order->status;
//            $order->changeStatus($status);
//            $order->save();
//
//            $order->order_statusues()->create(['from_status' => $old_status, 'to_status' => $status, 'user_id' => auth()->guard('seller')->user()->id]);
            $orderService = new OrderService;
            $user = auth()->guard('seller')->user();
            $orderService->storeOrderStatus($order, $status, $user);

            return back()->with('success', __('Status updated successfully.'));
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
