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

class SellerSalesExport implements FromView, ShouldAutoSize
{
    use Exportable;
    
    protected $startDate;
    protected $endDate;
    protected $status;

   public function __construct($startDate, $endDate, $status)
   {
      $this->startDate = $startDate;
      $this->endDate = $endDate;
      $this->status = $status;
   }

    public function view(): View
    {
        
        $query= Order::where('customer_id','>',0);
        
            if ( !empty($this->startDate) && !empty($this->endDate)) {
            $startDate = $this->startDate;
            $endDate = $this->endDate;

            $query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }
        

        if (!empty($this->status)) {

            $query->where('status', $this->status);
        }
        $records = $query->latest()->get();
                        
        return view('exports.sales', [
            
            
            'orders' => $records
        ]);
    }
}
