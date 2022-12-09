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
use DB;

class GroupSaleStoresExport implements FromView, ShouldAutoSize {

    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $status;
    protected $groupby;
    protected $store;

    public function __construct($startDate, $endDate, $status, $groupby, $store) 
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->groupby = $groupby;
        $this->store = $store;
    }

    public function view(): View 
    {
        $query = StoreOrder::with('seller');

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $startDate = $this->startDate;
            $endDate = $this->endDate;
            $query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }
        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        if (!empty($this->store)) {
            $query->where('store_id', $this->store);
        }

        if (!empty($this->groupby)) {
            if ($this->groupby == 'Daily') {
                $query->select(DB::raw('store_id'), DB::raw('count(id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'), DB::raw(' DATE(created_at) date'));
                $query->groupBy('store_id', DB::raw('YEAR(created_at) , MONTH(created_at) , DATE(created_at)'));
            }
            if ($this->groupby == 'Monthly') {
                $query->select(DB::raw('store_id'), DB::raw('count(id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'), DB::raw("DATE_FORMAT(created_at, '%Y-%M') AS date"));
                $query->groupBy('store_id', 'date');
            }
            if ($this->groupby == 'Yearly') {
                $query->select(DB::raw('store_id'), DB::raw('count(id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'), DB::raw('YEAR(created_at) date '));
                $query->groupBy('store_id', DB::raw('YEAR(created_at) '));
            }
        } else {
            $query->select(DB::raw('store_id'), DB::raw('count(id) as countTotal'), DB::raw('SUM(sub_total) as amountTotal'), DB::raw('SUM(delivery_fee) as deliveryFee'), DB::raw(' DATE(created_at) date'));
            $query->groupBy('store_id', DB::raw('DATE(created_at) '));
        }

        $records = $query->get();

        return view('exports.group-sale-stores', [
            'orders' => $records
        ]);
    }

}
