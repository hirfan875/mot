<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Service\StoreService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Attribute;
use App\Rules\Sku;
use App\Service\FilterCategoryService;
use App\Service\FilterTagsService;
use App\Service\MotFeeService;
use App\Service\ProductService;
use App\Service\ProductGalleryService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SampleProducts;
use App\Exports\SellerSalesExport;
use App\Exports\GroupSellerSalesExport;
use App\Exports\GroupSellerSaleStoresExport;
use App\Exports\GroupSellerSaleProductsExport;
use App\Exports\SellerCouponUsageExport;
use App\Exports\GroupSellerSaleCustomersExport;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
use App\Models\OrderItem;
use App\Models\StoreOrder;
use App\Service\OrderService;
use DB;
use App\Service\FilterStoreOrderService;
use App\Service\FilterOrderService;
use App\Service\FilterProductsService;
use App\Events\OrderStatusChange;
use App\Events\OrderDelivered;

class ReportsController extends Controller {

    /** @var \Monolog\Logger */
    private $logger;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth:seller']);
        $this->logger = getLogger('Reports Controller');
    }

    // show all records
    public function sales(Request $request) {
        $user = auth()->guard('seller')->user();
        $startDate = '';
        $endDate = '';
        $status = '';

        $query = Order::with('customer');

        if ($request->has('startDate') && !empty($request->startDate) && $request->has('endDate') && !empty($request->endDate)) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }

        if ($request->has('status') && !empty($request->status)) {
            $status = $request->status;
            $query->where('status', $status);
        }

        $records = $query->latest()->get();

        $data = [
            'title' => 'Orders',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'records' => $records
        ];

        return view('seller.reports.sales', $data);
    }

    // show all records
    public function groupSales(Request $request, FilterStoreOrderService $filterStoreOrderService, FilterProductsService $filterProductsService) {


        $user = auth()->guard('seller')->user();

        $startDate = '';
        $endDate = '';
        $status = '';
        $groupby = '';

        $query = StoreOrder::with('order.customer')->where('store_id', $user->store_id);

        if ($request->has('startDate') && !empty($request->startDate) && $request->has('endDate') && !empty($request->endDate)) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
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

        $records = $query->get();
        $data = [
            'title' => 'Group By Sales',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'records' => $records,
            'groupby' => $groupby
        ];

        return view('seller.reports.group-sales', $data);
    }

    // show all records
    public function groupSaleProducts(Request $request, FilterStoreOrderService $filterStoreOrderService, FilterProductsService $filterProductsService) {
        $startDate = '';
        $endDate = '';
        $status = '';
        $groupby = '';
        $store_id = '';

        $user = auth()->guard('seller')->user();
        $customers = Customer::orderBy('name', 'asc')->get();
        $stores = Store::orderBy('name', 'asc')->get();
        $products = $filterProductsService->setActiveFilter()->sortBy(['title' => 'asc'])->get();

        $storeService = new StoreService();
        $query = OrderItem::with(['product', 'store_order']);

        $query->whereHas('store_order', function ($query) use ($user) {
            return $query->where('store_id', $user->store_id);
        });

        if ($request->has('groupby') && !empty($request->groupby)) {
            $groupby = $request->groupby;

            if ($groupby == 'Daily') {
                $query->select('product_id', DB::raw('count(product_id) as countTotal'), DB::raw('SUM(unit_price*quantity) as unit_price'), DB::raw('SUM(quantity) as quantity'), DB::raw(' DATE(created_at) date'));
                $query->groupBy('product_id', DB::raw('YEAR(created_at) , MONTH(created_at) , DATE(created_at)'));
            }
            if ($groupby == 'Monthly') {
                $query->select('product_id', DB::raw('count(id) as countTotal'), DB::raw('SUM(unit_price*quantity) as unit_price'), DB::raw('SUM(quantity) as quantity'), DB::raw("DATE_FORMAT(created_at, '%Y-%M') AS date"));
                $query->groupBy('product_id', 'date');
            }
            if ($groupby == 'Yearly') {
                $query->select('product_id', DB::raw('count(product_id) as countTotal'), DB::raw('SUM(unit_price*quantity) as unit_price'), DB::raw('SUM(quantity) as quantity'), DB::raw('YEAR(created_at) date '));
                $query->groupBy('product_id', DB::raw('YEAR(created_at) '));
            }
        } else {
            $query->select('product_id', DB::raw('count(product_id) as countTotal'), DB::raw('SUM(unit_price*quantity) as unit_price'), DB::raw('SUM(quantity) as quantity'), DB::raw(' DATE(created_at) date'));
            $query->groupBy('product_id', DB::raw('DATE(created_at)'));
        }

        $records = $query->get();

        $data = [
            'title' => 'Group Sale By Products',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'records' => $records,
            'groupby' => $groupby,
            'store_id' => $store_id,
            'customers' => $customers,
            'stores' => $stores,
            'products' => $products,
        ];

        return view('seller.reports.group-sale-products', $data);
    }

    public function groupSaleCustomers(Request $request, FilterStoreOrderService $filterStoreOrderService, FilterProductsService $filterProductsService) {
        $startDate = '';
        $endDate = '';
        $status = '';
        $groupby = '';
        $store_id = '';

        $user = auth()->guard('seller')->user();

        $filterStoreOrderService->byStore($user->store_id)->active();

        if ($request->has('customer') && !empty($request->customer)) {
            $filterStoreOrderService->byCustomer($request->customer);
        }

        if ($request->has('store') && !empty($request->store)) {
            $filterStoreOrderService->byStore($request->store);
        }

        if ($request->has('product') && !empty($request->product)) {
            $filterStoreOrderService->byProduct($request->product);
        }

        if ($request->has('status') && !empty($request->status)) {
            $filterStoreOrderService->byStatus($request->status);
        }

        if ($request->has('startDate') && !empty($request->startDate) && $request->has('endDate') && !empty($request->endDate)) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $filterStoreOrderService->byDate($startDate, $endDate);
        }

        // todo replace get to paginate
        $records = $filterStoreOrderService->relations(['order.customer', 'order.currency', 'order.store_orders', 'order_items']);
        $records = $records->latest()->get();

        $customers = Customer::orderBy('name', 'asc')->get();
        $stores = Store::orderBy('name', 'asc')->get();
        $products = $filterProductsService->setActiveFilter()->sortBy(['title' => 'asc'])->get();

        return view('seller.reports.index', [
            'title' => __('Group Sale By Customer'),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'groupby' => $groupby,
            'records' => $records,
            'customers' => $customers,
            'stores' => $stores,
            'products' => $products,
            'request_params' => $request->toArray()
        ]);
    }

    public function couponUsage(Request $request, FilterStoreOrderService $filterStoreOrderService, FilterProductsService $filterProductsService) {
        $startDate = '';
        $endDate = '';
        $status = '';
        $groupby = '';
        $store_id = '';

        $user = auth()->guard('seller')->user();

        $filterStoreOrderService->byStore($user->store_id)->active();

        if ($request->has('customer') && !empty($request->customer)) {
            $filterStoreOrderService->byCustomer($request->customer);
        }

        if ($request->has('store') && !empty($request->store)) {
            $filterStoreOrderService->byStore($request->store);
        }

        if ($request->has('product') && !empty($request->product)) {
            $filterStoreOrderService->byProduct($request->product);
        }

        if ($request->has('status') && !empty($request->status)) {
            $filterStoreOrderService->byStatus($request->status);
        }

        if ($request->has('startDate') && !empty($request->startDate) && $request->has('endDate') && !empty($request->endDate)) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $filterStoreOrderService->byDate($startDate, $endDate);
        }
        $filterStoreOrderService->byCoupon();
        // todo replace get to paginate
        $records = $filterStoreOrderService->relations(['order.customer', 'order.coupon', 'order.currency', 'order.store_orders', 'order_items']);
        $records = $records->latest()->get();

        $customers = Customer::orderBy('name', 'asc')->get();
        $stores = Store::orderBy('name', 'asc')->get();
        $products = $filterProductsService->setActiveFilter()->sortBy(['title' => 'asc'])->get();

        return view('seller.reports.coupon', [
            'title' => __('Coupon Usage'),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'groupby' => $groupby,
            'records' => $records,
            'customers' => $customers,
            'stores' => $stores,
            'products' => $products,
            'request_params' => $request->toArray()
        ]);
    }

    // export data
    public function export($type, Request $request) {
        $filename = "sales.{$type}";
        ini_set('memory_limit', '-1');
        return (new SellerSalesExport($request->startDate, $request->endDate, $request->status))->download($filename);
    }

    // export data
    public function groupExport($type, Request $request) {
        $filename = "group-sales.{$type}";
        ini_set('memory_limit', '-1');
        return (new GroupSellerSalesExport($request->startDate, $request->endDate, $request->status, $request->groupby))->download($filename);
    }

    // export data
    public function groupSaleStoresExport($type, Request $request) {
        $filename = "group-sale-stores.{$type}";
        ini_set('memory_limit', '-1');
        return (new GroupSellerSaleStoresExport($request->startDate, $request->endDate, $request->status, $request->groupby))->download($filename);
    }

    // export data
    public function groupSaleProductsExport($type, Request $request) {
        $filename = "group-sale-products.{$type}";
        ini_set('memory_limit', '-1');
        return (new GroupSellerSaleProductsExport($request->startDate, $request->endDate, $request->status, $request->groupby))->download($filename);
    }

    // export data
    public function groupSaleCustomersExport($type, Request $request, FilterStoreOrderService $filterStoreOrderService) {
        $filename = "group-sale-products.{$type}";
        ini_set('memory_limit', '-1');
        return (new GroupSellerSaleCustomersExport($request->startDate, $request->endDate, $request->status, $request->product, $request->customer, $filterStoreOrderService))->download($filename);
    }

    // export data
    public function couponUsageExport($type, Request $request, FilterStoreOrderService $filterStoreOrderService) {
        $filename = "Coupon-Usage.{$type}";
        ini_set('memory_limit', '-1');
        return (new SellerCouponUsageExport($request->startDate, $request->endDate, $request->status, $request->product, $request->customer, $filterStoreOrderService))->download($filename);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSampleExcel(Request $request) {
        try {
            $filenameExport = "sample-products.xlsx";
            return Excel::download(new SampleProducts(), $filenameExport);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}
