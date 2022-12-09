<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;

class ProductReviewsController extends Controller
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
        $records = ProductReview::whereIsApproved(true)->whereHas('customer')->whereHas('order_item', function ($query) use ($user) {
            $query->whereHas('store_order', function ($query) use ($user) {
                $query->whereStoreId($user->store_id);
            });
        })->with(['customer', 'order_item.product'])->latest()->get();

        return view('seller.product-reviews.index', [
            'title' => __('Product Reviews'),
            'records' => $records
        ]);
    }
    
    public function show(ProductReview $item)
    {
        $records = ProductReview::whereHas('customer')->whereHas('order_item')->with(['customer', 'order_item.product','gallery'])->where('id',$item->id)->first();
        
        return view('admin.product-reviews.show', [
            'title' => __('Product Reviews'),
            'records' => $records
        ]);
    }
}
