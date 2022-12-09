<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancelRequest;
use App\Service\FilterCancelRequestService;
use Illuminate\Http\Request;

class CancelRequestsController extends Controller
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
    public function index(FilterCancelRequestService $filterCancelRequestService)
    {
        
        $records = $filterCancelRequestService
            ->relations(['store_order.order.customer'])
            ->pending()
            ->latest()
            ->get();
//        dd($records);
        return view('admin.cancel-requests.index', [
            'title' => __('Cancel Requests'),
            'records' => $records
        ]);
    }
}
