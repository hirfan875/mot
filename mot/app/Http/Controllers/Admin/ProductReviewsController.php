<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware(['auth']);
        $this->middleware('permission:product-reviews-list|product-reviews-show|product-reviews-approve|product-reviews-reject', ['only' => ['index','approve']]);
        $this->middleware('permission:product-reviews-show', ['only' => ['show']]);
        $this->middleware('permission:product-reviews-approve', ['only' => ['approve']]);
        $this->middleware('permission:product-reviews-reject', ['only' => ['reject']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = ProductReview::whereHas('customer')->whereHas('order_item')->with(['customer', 'order_item.product'])->latest()->get();

        return view('admin.product-reviews.index', [
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

    /**
     * approve review
     *
     * @param ProductReview $item
     * @return \Illuminate\Http\Response
     */
    public function approve(ProductReview $item)
    {
        $item->is_approved = true;
        $item->save();

        return back()->with('success', __('Review approved successfully.'));
    }

    /**
     * reject review
     *
     * @param ProductReview $item
     * @return \Illuminate\Http\Response
     */
    public function reject(ProductReview $item)
    {
        $item->delete();
        return back()->with('success', __('Review rejected successfully.'));
    }
}
