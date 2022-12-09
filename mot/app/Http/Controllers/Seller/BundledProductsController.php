<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Service\FilterProductsService;
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
        $this->middleware('auth:seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->guard('seller')->user();
        $filterProductsService = new FilterProductsService();
        $records = $filterProductsService->byStore($user->store_id)->byType(Product::TYPE_BUNDLE)->relations(['brand'])->latest()->get();

        return view('seller.products.index', [
            'title' => __('Bundled Products'),
            'records' => $records
        ]);
    }
}
