<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Service\StoreService;
use Illuminate\Http\Request;

class BundledProductsController extends Controller
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
    public function index(Request $request)
    {
        $storeService = new StoreService();
        $records = Product::whereNull('parent_id')->whereType(Product::TYPE_BUNDLE)->with('brand')->latest()->paginate(15);

        return view('admin.products.index', [
            'title' => __('Bundled Products'),
            'records' => $records,
            'stores' => $storeService->getAll()
        ]);
    }
}
