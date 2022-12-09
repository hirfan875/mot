<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
use App\Models\StoreOrder;
use App\Models\OrderItem;
use App\Service\OrderService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;
use DB;

class GroupSaleProductsExport implements FromView, ShouldAutoSize
{
    use Exportable;
    
    protected $startDate;
    protected $endDate;
    protected $status;

   public function __construct($startDate, $endDate, $status,$groupby)
   {
      $this->startDate = $startDate;
      $this->endDate = $endDate;
      $this->status = $status;
      $this->groupby = $groupby;
   }

    public function view(): View
    {
//        $storeService = new StoreService();
        $query = OrderItem::with(['product', 'store_order']);
        if ( !empty($this->startDate) && !empty($this->endDate)) {
            $startDate = $this->startDate;
            $endDate = $this->endDate;
            $query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }
        if (!empty($this->groupby)) {
            $groupby = $this->groupby;

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
                        
        return view('exports.group-sale-products', [
            'orders' => $records
        ]);
    }
}
