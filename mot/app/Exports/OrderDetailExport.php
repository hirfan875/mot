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

class OrderDetailExport implements FromView, ShouldAutoSize
{
    use Exportable;
    
    protected $storeOrder;

   public function __construct($storeOrder)
   {
      $this->storeOrder = $storeOrder;
   }

    public function view(): View
    {
        
        $store_order = $this->storeOrder->load(['order.customer', 'order.currency', 'seller', 'order_items.product','shipment_requests','shipment_reponse','pickup_reponse']);
        $status_buttons = $this->storeOrder->getPossibleStatusButtonAdmin();
                    
        return view('exports.ordersdetail', [
            'title' => __('Order Detail'),
            'section_title' => __('Orders'),
            'store_order' => $store_order,
            'status_buttons' => $status_buttons
        ]);
    }
}
