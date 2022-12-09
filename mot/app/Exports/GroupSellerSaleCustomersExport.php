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

class GroupSellerSaleCustomersExport implements FromView, ShouldAutoSize
{
    use Exportable;
    
    protected $startDate;
    protected $endDate;
    protected $status;
    protected $product;
    protected $customer;
    protected $filterStoreOrderService;

   public function __construct($startDate, $endDate, $status, $product, $customer,$filterStoreOrderService)
   {
      $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->customer = $customer;
        $this->product = $product;
        $this->filterStoreOrderService = $filterStoreOrderService;
   }

    public function view(): View
    {
        $user = auth()->guard('seller')->user();
        $filterStoreOrderService->byStore($user->store_id)->active();
        
        if (!empty($this->customer)) {
            $this->filterStoreOrderService->byCustomer($this->customer);
        }

        if (!empty($this->status)) {
            $this->filterStoreOrderService->byStatus($this->status);
        }

        if (!empty($this->product)) {
            $this->filterStoreOrderService->byProduct($this->product);
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
