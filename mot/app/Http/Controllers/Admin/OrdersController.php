<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
use App\Models\StoreOrder;
use App\Service\FilterOrderService;
use App\Service\FilterProductsService;
use Illuminate\Http\Request;
use App\Service\OrderService;
use App\Events\OrderStatusChange;
use App\Service\FilterStoreOrderService;
use App\Events\OrderDelivered;
use App\Exports\OrderDetailExport;
//use Dompdf\Dompdf;
//use Dompdf\Options;
// use Barryvdh\DomPDF\Facade\Pdf;
 use PDF;

class OrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FilterOrderService $filterOrderService, FilterProductsService $filterProductsService)
    {
        if ($request->has('customer') && !empty($request->customer)) {
            $filterOrderService->byCustomer($request->customer);
        }

        if ($request->has('store') && !empty($request->store)) {
            $filterOrderService->byStore($request->store);
        }

        if ($request->has('product') && !empty($request->product)) {
            $filterOrderService->byProduct($request->product);
        }

        // todo replace get to paginate
        $records = $filterOrderService->relations(['customer', 'currency', 'store_orders', 'order_items'])->latest()->get();
        $customers = Customer::orderBy('name', 'asc')->get();
        $stores = Store::orderBy('name', 'asc')->get();
        $products = $filterProductsService->setActiveFilter()->sortBy(['title' => 'asc'])->get();

        return view('admin.orders.index', [
            'title' => __('Orders'),
            'records' => $records,
            'customers' => $customers,
            'stores' => $stores,
            'products' => $products,
            'request_params' => $request->toArray()
        ]);
    }

    /**
     * Show the detail for specified resource.
     *
     * @param StoreOrder $storeOrder
     * @return \Illuminate\Http\Response
     */
    public function detail(Order $order)
    {
//        $storeOrder->load(['customer', 'currency', 'seller', 'order_items.product','shipment_requests','shipment_reponse','pickup_reponse']);
        $order->load(['customer', 'currency', 'seller', 'order_items.product']);
        $status_buttons = $order->getPossibleStatusButtonAdmin();

        return view('admin.orders.detail', [
            'title' => __('Order Detail'),
            'section_title' => __('Orders'),
            'order' => $order,
            'status_buttons' => $status_buttons
        ]);
    }

    /**
     * Show the overview for specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function overview(Order $order)
    {
        $order->load(['order_items.product', 'currency']);

        return view('admin.orders.overview', [
            'title' => __('Order').' # '.$order->order_number,
            'order' => $order
        ]);
    }

    public function pendingOrders(Request $request, FilterOrderService $filterOrderService, FilterProductsService $filterProductsService)
    {
        if ($request->has('customer') && !empty($request->customer)) {
            $filterOrderService->byCustomer($request->customer);
        }

        if ($request->has('store') && !empty($request->store)) {
            $filterOrderService->byStore($request->store);
        }

        if ($request->has('product') && !empty($request->product)) {
            $filterOrderService->byProduct($request->product);
        }
        $filterOrderService->byStatus([Order::UNIITIATED_ID, Order::CONFIRMED_ID ]);

        // todo replace get to paginate
        $records = $filterOrderService->relations(['customer', 'currency', 'store_orders', 'order_items'])->latest()->getUninitiated();
        $customers = Customer::orderBy('name', 'asc')->get();
        $stores = Store::orderBy('name', 'asc')->get();
        $products = $filterProductsService->setActiveFilter()->sortBy(['title' => 'asc'])->get();
        
        return view('admin.pending-orders.index', [
            'title' => __('Uninitiated Orders'),
            'records' => $records,
            'customers' => $customers,
            'stores' => $stores,
            'products' => $products,
            'request_params' => $request->toArray()
        ]);
    }

    /**
     * Show the detail for specified resource.
     *
     * @param StoreOrder $storeOrder
     * @return \Illuminate\Http\Response
     */
    public function pendingOrdersDetail(Order $order)
    {
        $order->load(['customer', 'currency', 'seller', 'order_items.product']);
        $status_buttons = $order->getPossibleStatusButtonAdmin();

        return view('admin.pending-orders.detail', [
            'title' => __('Pending Order Detail'),
            'section_title' => __('Pending Orders'),
            'order' => $order,
            'status_buttons' => $status_buttons
        ]);
        
    }

    /**
     * update order status
     *
     * @param Order $storeOrder
     * @param int $status
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Order $storeOrder, int $status)
    {
        try {
            $orderService = new OrderService;
            $user = auth()->user();
            $orderService->orderStatus($storeOrder, $status, $user);

            return back()->with('success', __('Status updated successfully.'));

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function updateOrderFrom(Order $order, int $status)
    {
        try {
            $user = auth()->user();
            $order->order_from = 'Whatsapp';
            $order->save();

            return back()->with('success', __('Status updated successfully.'));

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    

    // export data
    public function exportDetail(Order $order) {

        $order->load(['customer', 'currency', 'seller', 'order_items.product','store_orders.shipment_requests','store_orders.shipment_reponse','store_orders.pickup_reponse']);
        $status_buttons = $order->getPossibleStatusButtonAdmin();

        $data = [
            'title' => __('Order Detail'),
            'section_title' => __('Orders'),
            'orders' => $order,
            'status_buttons' => $status_buttons
        ];

        /* jab bhi view check karna ho yeh code open karlie ga ok*/
        //return view('exports.ordersdetail', $data);

        /* yeh wali line s se ap doheet daikh saktay han ok*/
        $pdf = PDF::setOptions(['images' => true])
//            ->setOptions(['isPhpEnabled' => true])
            ->loadView('exports.ordersdetail',$data);
        $pdf->setOptions(['dpi' => 110, 'defaultFont' => 'sans-serif']);
        $pdf->setPaper('a4', 'landscape')->setWarnings(false);
         ini_set('memory_limit', '-1');
//         return $pdf->stream();
         
         $filename = isset($order->order_number) ? $order->order_number : $order->id   ;
          return $pdf->download($filename. "-invoice.pdf");

    }
}
