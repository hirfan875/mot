<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreOrder;
use Illuminate\Http\Request;
use DB;
use App\Models\OrderItem;

class DashboardController extends Controller
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

    // show dashboard page
    public function index(Request $request)
    {
        $startDate = '';
        $endDate = '';
        $status = '';
        $groupby = '';

        $query = StoreOrder::with('order.customer')->whereHas('order',  function($query){ $query->whereNotIn('status', [Order::UNIITIATED_ID, Order::CONFIRMED_ID, Order::CANCELLED_ID, Order::DELIVERY_FAILURE_ID]);});

        if ($request->has('startDate') && !empty($request->startDate) && $request->has('endDate') && !empty($request->endDate)) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        } else {
            $startDate = date("Y-m-d", strtotime("-10 days"));
            $endDate = date("Y-m-d");
            $query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }

        if ($request->has('status') && !empty($request->status)) {
            $status = $request->status;
            $query->where('status', $status);
        }

        if ($request->has('groupby') && !empty($request->groupby)) {
            $groupby = $request->groupby;

            if ($groupby == 'Daily') {
                $query->select(DB::raw('count(id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'), DB::raw(' DATE(created_at) date'));
                $query->groupBy(DB::raw(DB::raw('YEAR(created_at) , MONTH(created_at) , DATE(created_at)')));
            }
            if ($groupby == 'Monthly') {
                $query->select(DB::raw('count(id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'), DB::raw("DATE_FORMAT(created_at, '%Y-%M') AS date"));
                $query->groupBy('date');
            }
            if ($groupby == 'Yearly') {
                $query->select(DB::raw('count(id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'), DB::raw('YEAR(created_at) date '));
                $query->groupBy(DB::raw(DB::raw('YEAR(created_at) ')));
            }
        } else {
            $query->select(DB::raw('count(id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'), DB::raw(' DATE(created_at) date'));
            $query->groupBy(DB::raw(DB::raw('DATE(created_at) ')));
        }

        $sales = $query->get();

        $query1 = StoreOrder::with('seller')->whereHas('order',  function($query){ $query->whereNotIn('status', [Order::UNIITIATED_ID, Order::CONFIRMED_ID, Order::CANCELLED_ID, Order::DELIVERY_FAILURE_ID]);});

        if ($request->has('startDate') && !empty($request->startDate) && $request->has('endDate') && !empty($request->endDate)) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $query1->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        } else {
            $startDate = date("Y-m-d", strtotime("-10 days"));
            $endDate = date("Y-m-d");
            $query1->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }

        if ($request->has('status') && !empty($request->status)) {
            $status = $request->status;
            $query1->where('status', $status);
        }

        if ($request->has('store') && !empty($request->store)) {
            $store_id = $request->store;
            $query1->where('store_id', $store_id);
        }

        if ($request->has('groupby') && !empty($request->groupby)) {
            $groupby = $request->groupby;

            if ($groupby == 'Daily') {
                $query1->select(DB::raw('store_id'), DB::raw('count(store_id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'));
                $query1->groupBy('store_id');
            }
            if ($groupby == 'Monthly') {
                $query1->select(DB::raw('store_id'), DB::raw('count(store_id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'));
                $query1->groupBy('store_id');
            }
            if ($groupby == 'Yearly') {
                $query1->select(DB::raw('store_id'), DB::raw('count(store_id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'));
                $query1->groupBy('store_id');
            }
        } else {
            $query1->select(DB::raw('store_id'), DB::raw('count(store_id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'));
            $query1->groupBy('store_id');
        }

        $storeSales = $query1->get();

        $query2 = OrderItem::with(['product', 'store_order'])->whereHas('store_order.order',  function($query){ $query->whereNotIn('status', [Order::UNIITIATED_ID, Order::CONFIRMED_ID, Order::CANCELLED_ID, Order::DELIVERY_FAILURE_ID]);});

        if ($request->has('startDate') && !empty($request->startDate) && $request->has('endDate') && !empty($request->endDate)) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $query2->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        } else {
            $startDate = date("Y-m-d", strtotime("-10 days"));
            $endDate = date("Y-m-d");
            $query2->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }

        if ($request->has('groupby') && !empty($request->groupby)) {
            $groupby = $request->groupby;

            if ($groupby == 'Daily') {
                $query2->select('product_id', DB::raw('count(product_id) as countTotal'), DB::raw('SUM(unit_price*quantity) as unit_price'), DB::raw('SUM(quantity) as quantity'));
                $query2->groupBy('product_id');
            }
            if ($groupby == 'Monthly') {
                $query2->select('product_id', DB::raw('count(id) as countTotal'), DB::raw('SUM(unit_price*quantity) as unit_price'), DB::raw('SUM(quantity) as quantity'));
                $query2->groupBy('product_id');
            }
            if ($groupby == 'Yearly') {
                $query2->select('product_id', DB::raw('count(product_id) as countTotal'), DB::raw('SUM(unit_price*quantity) as unit_price'), DB::raw('SUM(quantity) as quantity'));
                $query2->groupBy('product_id');
            }
        } else {
            $query2->select('product_id', DB::raw('count(product_id) as countTotal'), DB::raw('SUM(unit_price*quantity) as unit_price'), DB::raw('SUM(quantity) as quantity'));
            $query2->groupBy('product_id');
        }

        $products = $query2->get();

        $total_orders = Order::whereNotIn('status', [Order::UNIITIATED_ID, Order::CONFIRMED_ID, Order::CANCELLED_ID, Order::DELIVERY_FAILURE_ID, Order::TERMINATED_ID])->count();
        $total_customers = Customer::count();
        $total_stores = Store::approved()->where('status', 1)->count();
        $total_products = Product::whereNull('parent_id')->whereHas('store',  function($query){ $query->approved()->where('status', 1);})->count();
        $total_active_products = Product::whereNull('parent_id')->active()->whereHas('store',  function($query){ $query->approved()->where('status', 1);})->count();
        $total_cancelled_orders = StoreOrder::whereStatus(StoreOrder::CANCELLED_ID)->count();
        $total_returned_orders = StoreOrder::whereStatus(StoreOrder::RETURN_ACCEPTED_ID)->count();

        return view('admin.dashboard', [
            'title' => __('Dashboard'),
            'total_orders' => $total_orders,
            'total_customers' => $total_customers,
            'total_stores' => $total_stores,
            'total_products' => $total_products,
            'total_active_products' => $total_active_products,
            'sales' => $sales,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'groupby' => $groupby,
            'storeSales' => $storeSales,
            'products' => $products,
            'total_orders_return' => $total_returned_orders,
            'total_cancelled_orders' => $total_cancelled_orders,
        ]);
    }

}
