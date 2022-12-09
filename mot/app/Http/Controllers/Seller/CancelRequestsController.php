<?php

namespace App\Http\Controllers\Seller;

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
        $this->middleware(['auth:seller']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterCancelRequestService $filterCancelRequestService)
    {
        $user = auth()->user();
        $records = $filterCancelRequestService
            ->relations(['store_order.order.customer'])
            ->byStore($user->store_id)
            ->pending()
            ->latest()
            ->get();

        return view('seller.cancel-requests.index', [
            'title' => __('Cancel Requests'),
            'records' => $records
        ]);
    }
}