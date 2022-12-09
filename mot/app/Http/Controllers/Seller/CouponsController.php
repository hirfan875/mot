<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Product;
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
        $this->middleware('auth:seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sellerStaff = auth()->user();
        $records = Coupon::whereStoreId($sellerStaff->store_id)->latest()->get();

        return view('seller.coupons.index', [
            'title' => __('Coupons'),
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
        $sellerStaff = auth()->user();
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $selected_products = old('products', []);
        $selected_products = Product::active()->whereIn('id', $selected_products)->whereStoreId($sellerStaff->store_id)->get();

        return view('seller.coupons.add', [
            'title' => __('Add Coupon'),
            'section_title' => __('Coupons'),
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
        // authorize user
        $this->authorize('canView', $coupon);

        $sellerStaff = auth()->user();
        $coupon->load(['products', 'categories']);
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $selected_products = old('products', $coupon->products->pluck('id')->toArray());
        $selected_products = Product::active()->whereIn('id', $selected_products)->whereStoreId($sellerStaff->store_id)->get();

        return view('seller.coupons.edit', [
            'title' => __('Edit Coupon'),
            'section_title' => __('Coupons'),
            'row' => $coupon,
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
            'coupon_code' => 'required|alpha_num|min:5|max:20|unique:coupons',
            'discount' => 'required|integer|min:1|max:100',
        ]);

        $couponService = new CouponService();
        $couponService->create($request->toArray());

        return redirect()->route('seller.coupons')->with('success', __('Record added successfully.'));
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
        // authorize user
        $this->authorize('canUpdate', $coupon);

        $request->validate([
            'coupon_code' => 'required|alpha_num|min:5|max:20|unique:coupons,coupon_code,'.$coupon->id,
            'discount' => 'required|integer|min:1|max:100',
        ]);

        $couponService = new CouponService();
        $couponService->update($coupon, $request->toArray());

        return redirect()->route('seller.coupons')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function delete(Coupon $coupon)
    {
        // authorize user
        $this->authorize('canDelete', $coupon);

        $coupon->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }
}
