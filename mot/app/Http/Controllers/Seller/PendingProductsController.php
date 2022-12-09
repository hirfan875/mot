<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;

class PendingProductsController extends Controller
{
    /** @var \Monolog\Logger */
    private $logger;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:seller']);
        $this->logger = getLogger('Seller Pending Product Controller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $records = Product::whereIsApproved(false)
            ->whereNull('parent_id')
            ->where('store_id', $user->store_id)
            ->with('brand')
            ->latest()->get();

        return view('seller.pending-products.index', [
            'title' => __('Pending Products'),
            'records' => $records
        ]);
    }
}
