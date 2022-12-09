<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
use App\Models\StoreOrder;
use App\Service\OrderService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;
use App\Service\FilterStoreOrderService;

class GroupSaleCustomersExport implements FromView, ShouldAutoSize {

    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $status;
    protected $store;
    protected $product;
    protected $customer;
    protected $category;
    protected $filterStoreOrderService;

    public function __construct($startDate, $endDate, $status, $store, $product, $customer, $category, $filterStoreOrderService) 
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->store = $store;
        $this->product = $product;
        $this->customer = $customer;
        $this->category = $category;
        $this->filterStoreOrderService = $filterStoreOrderService;
    }

    public function view(): View 
    {

        if (!empty($this->customer)) {
            $this->filterStoreOrderService->byCustomer($this->customer);
        }
        if (!empty($this->status)) {
            $this->filterStoreOrderService->byStatus($this->status);
        }
        if (!empty($this->product)) {
            $this->filterStoreOrderService->byProduct($this->product);
        }
        if (!empty($this->store)) {
            $this->filterStoreOrderService->byStore($this->store);
        }
        if (!empty($this->category)) {
            $this->filterStoreOrderService->byCategory($this->category);
        }
        if (!empty($this->startDate) && !empty($this->endDate)) {
            $startDate = $this->startDate;
            $endDate = $this->endDate;
            $this->filterStoreOrderService->byDate($startDate, $endDate);
        }
        // todo replace get to paginate
        $records = $this->filterStoreOrderService->relations(['order.customer', 'order.coupon', 'order.currency', 'order.store_orders', 'order_items']);
        $records = $records->latest()->get();

        return view('exports.customer', [
            'orders' => $records
        ]);
    }

}
