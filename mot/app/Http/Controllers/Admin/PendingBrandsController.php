<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;

class PendingBrandsController extends Controller
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
        $this->logger = getLogger('Pending Brand Controller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Brand::whereIsApproved(false)
            ->with('products')
            ->latest()->get();

        return view('admin.pending-brands.index', [
            'title' => __('Pending Brands'),
            'records' => $records
        ]);
    }
}
