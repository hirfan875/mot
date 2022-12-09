<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;

class PendingStoresController extends Controller
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
        $this->logger = getLogger('Pending Store Controller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Store::pending()->with('country')->latest()->get();

        return view('admin.pending-stores.index', [
            'title' => __('Pending Stores'),
            'records' => $records
        ]);
    }
}
