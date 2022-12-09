<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Service\FilterProductsService;

class FreeDeliveryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filterProductsService = new FilterProductsService();
        $records = $filterProductsService->byFreeDelivery()->relations(['brand'])->latest()->get();

        return view('admin.free-delivery.index', [
            'title' => __('Free Delivery Products'),
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.free-delivery.add', [
            'title' => __('Add Free Delivery Products'),
            'section_title' => __('Free Delivery Products')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
        ]);

        Product::whereIn('id', $request->products)->update(['free_delivery' => true]);

        return redirect()->route('admin.free.delivery')->with('success', __('Record added successfully.'));
    }
}
