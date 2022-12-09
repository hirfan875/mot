<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware(['auth']);
        $this->logger = getLogger('Pending Product Controller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Product::whereIsApproved(false)
            ->whereNull('parent_id')
            ->with('brand')
            ->latest()->get();

        return view('admin.pending-products.index', [
            'title' => __('Pending Products'),
            'records' => $records
        ]);
    }
}
