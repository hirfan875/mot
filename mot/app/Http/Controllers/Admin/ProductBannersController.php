<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductBanner;
use App\Service\FilterCategoryService;
use App\Service\Media;
use App\Service\ProductBannerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductBannersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:pending-brands-list|pending-brands-create|pending-brands-edit|pending-brands-delete', ['only' => ['index','store']]);
        $this->middleware('permission:pending-brands-create', ['only' => ['create','store']]);
        $this->middleware('permission:pending-brands-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:pending-brands-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = ProductBanner::with('categories.category_translates')->get();

        return view('admin.product-banners.index', [
            'title' => __('Product List Page Banners'),
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
        $categories = $filterCategoryService->active()->withSubcategories()->get();

        return view('admin.product-banners.add', [
            'title' => __('Add Banner'),
            'section_title' => __('Product List Page Banners'),
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductBanner $productBanner
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductBanner $productBanner, FilterCategoryService $filterCategoryService)
    {
        $categories = $filterCategoryService->active()->withSubcategories()->get();

        return view('admin.product-banners.edit', [
            'title' => __('Edit Banner'),
            'section_title' => __('Product List Page Banners'),
            'row' => $productBanner,
            'categories' => $categories
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
            'banner_1' => 'required|mimes:jpg,jpeg,png',
            'banner_1_url' => 'required',
            'banner_2' => 'sometimes|mimes:jpg,jpeg,png',
            'categories' => 'required|array|min:1'
        ]);

        $productBannerService = new ProductBannerService();
        $productBannerService->create($request->toArray());

        return redirect()->route('admin.product.banners')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ProductBanner $productBanner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBanner $productBanner)
    {
        $request->validate([
            'banner_1' => 'sometimes|mimes:jpg,jpeg,png',
            'banner_1_url' => 'required',
            'banner_2' => 'sometimes|mimes:jpg,jpeg,png',
            'categories' => [Rule::requiredIf(!$productBanner->is_default), 'array', 'min:1']
        ]);

        $productBannerService = new ProductBannerService();
        $productBannerService->update($productBanner, $request->toArray());

        return redirect()->route('admin.product.banners')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductBanner $productBanner
     * @return \Illuminate\Http\Response
     */
    public function delete(ProductBanner $productBanner)
    {
        if ($productBanner->is_default) {
            return back()->with('error', __('You cannot delete this record.'));
        }

        $productBanner->delete();
        Media::delete($productBanner->banner_1);
        Media::delete($productBanner->banner_2);

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
        $productBanner = ProductBanner::findOrFail($request->id);
        $productBanner->status = $request->value;
        $productBanner->save();
    }
}
