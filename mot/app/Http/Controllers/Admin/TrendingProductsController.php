<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrendingProduct;
use App\Service\FilterCategoryService;
use App\Service\FilterTagsService;
use App\Service\HomepageSectionsService;
use App\Service\TrendingProductService;
use Illuminate\Http\Request;

class TrendingProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:trending-products-list|trending-products-create|trending-products-edit|trending-products-delete', ['only' => ['index','store']]);
        $this->middleware('permission:trending-products-create', ['only' => ['create','store']]);
        $this->middleware('permission:trending-products-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:trending-products-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = TrendingProduct::latest()->get();

        return view('admin.trending-products.index', [
            'title' => __('Trending Products'),
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FilterCategoryService $filterCategoryService, FilterTagsService $filterTagsService)
    {
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $tags = $filterTagsService->get();

        return view('admin.trending-products.add', [
            'title' => __('Add Section'),
            'section_title' => __('Trending Products'),
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TrendingProduct $item
     * @return \Illuminate\Http\Response
     */
    public function edit(TrendingProduct $item, FilterCategoryService $filterCategoryService, FilterTagsService $filterTagsService)
    {
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $tags = $filterTagsService->get();

        return view('admin.trending-products.edit', [
            'title' => __('Edit Section'),
            'section_title' => __('Trending Products'),
            'row' => $item,
            'categories' => $categories,
            'tags' => $tags
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
            'title' => 'required',
            'category_id' => 'required_if:type,category',
            'products' => 'required_if:type,product|array|min:1',
        ]);

        $tabbedProductService = new TrendingProductService();
        $tabbedProductService->create($request->toArray());

        return redirect()->route('admin.trending.products')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TrendingProduct $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrendingProduct $item)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'category_id' => 'required_if:type,category',
            'products' => 'required_if:type,product|array|min:1',
        ]);

        $tabbedProductService = new TrendingProductService();
        $tabbedProductService->update($item, $request->toArray());

        return redirect()->route('admin.trending.products')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TrendingProduct $item
     * @return \Illuminate\Http\Response
     */
    public function delete(TrendingProduct $item)
    {
        $sort_order = $item->sort->sort_order;
        $item->sort->delete();
        $item->delete();

        $homepageSectionsService = new HomepageSectionsService();
        $homepageSectionsService->decrement_sort_order($sort_order);

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
        $section = TrendingProduct::findOrFail($request->id);
        $section->status = $request->value;
        $section->save();
    }
}
