<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;

class RejectedStoresController extends Controller
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
        $this->logger = getLogger('Rejected Store Controller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Store::rejected()->with('country')->latest()->get();

        return view('admin.rejected-stores.index', [
            'title' => __('Rejected Stores'),
            'records' => $records
        ]);
    }
}
