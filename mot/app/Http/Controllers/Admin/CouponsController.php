<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Store;
use App\Service\CouponService;
use App\Service\FilterCategoryService;

class CouponsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:coupons-list|coupons-create|coupons-edit|coupons-delete', ['only' => ['index','store']]);
        $this->middleware('permission:coupons-create', ['only' => ['create','store']]);
        $this->middleware('permission:coupons-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:coupons-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Coupon::with('store')->latest()->get();

        return view('admin.coupons.index', [
            'title' => __('Discount Campaigns'),
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FilterCategoryService $filterCategoryService)
    {
        $stores = Store::where('status', true)->whereIsApproved(Store::STATUS_APPROVED)->orderBy('name', 'asc')->get();
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $selected_products = old('products', []);
        $selected_products = Product::active()->whereIn('id', $selected_products)->get();

        return view('admin.coupons.add', [
            'title' => __('Add DiscountCampaigns'),
            'section_title' => __('Discount Campaigns'),
            'stores' => $stores,
            'categories' => $categories,
            'selected_products' => $selected_products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon, FilterCategoryService $filterCategoryService)
    {
        $coupon->load(['products', 'categories']);
        $stores = Store::where('status', true)->whereIsApproved(Store::STATUS_APPROVED)->orderBy('name', 'asc')->get();
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $selected_products = old('products', $coupon->products->pluck('id')->toArray());
        $selected_products = Product::active()->whereIn('id', $selected_products)->get();

        return view('admin.coupons.edit', [
            'title' => __('Edit Discount Campaigns'),
            'section_title' => __('Discount Campaigns'),
            'row' => $coupon,
            'stores' => $stores,
            'categories' => $categories,
            'selected_products' => $selected_products
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
            'title' => 'required|min:3',
            'coupon_code' => 'nullable|alpha_num|max:10|unique:coupons',
//            'discount' => 'required|integer|min:1|max:100',
        ]);

        $couponService = new CouponService();
        $couponService->create($request->toArray(), true);

        return redirect()->route('admin.coupons')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'title' => 'required|min:3',
            'coupon_code' => 'nullable|max:10|unique:coupons,coupon_code,'.$coupon->id,
//            'discount' => 'required|integer|min:1|max:100',
        ]);

        $couponService = new CouponService();
        $couponService->update($coupon, $request->toArray());

        return redirect()->route('admin.coupons')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function delete(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * update status
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->value;
        $coupon->save();
    }
}
