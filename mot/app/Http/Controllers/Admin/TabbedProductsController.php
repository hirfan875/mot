<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TabbedSection;
use App\Service\FilterCategoryService;
use App\Service\FilterProductsService;
use App\Service\HomepageSectionsService;
use App\Service\TabbedProductService;
use Illuminate\Http\Request;

class TabbedProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:tabbed-products-list|tabbed-products-create|tabbed-products-edit|tabbed-products-delete', ['only' => ['index','store']]);
        $this->middleware('permission:tabbed-products-create', ['only' => ['create','store']]);
        $this->middleware('permission:tabbed-products-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:tabbed-products-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = TabbedSection::latest()->get();

        return view('admin.tabbed-products.index', [
            'title' => __('Tabbed Products'),
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FilterCategoryService $filterCategoryService, FilterProductsService $filterProductsService)
    {
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $products = $filterProductsService->setActiveFilter()->sortBy(['title' => 'asc'])->get();

        return view('admin.tabbed-products.add', [
            'title' => __('Add Section'),
            'section_title' => __('Tabbed Products'),
            'categories' => $categories,
            'products' => $products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TabbedSection $item
     * @return \Illuminate\Http\Response
     */
    public function edit(TabbedSection $item, FilterCategoryService $filterCategoryService, FilterProductsService $filterProductsService)
    {
        $item->load(['products']);
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $products = $filterProductsService->setActiveFilter()->sortBy(['title' => 'asc'])->get();

        // set item data variables
        $section_products = $item->products->pluck('id')->toArray();

        return view('admin.tabbed-products.edit', [
            'title' => __('Edit Section'),
            'section_title' => __('Tabbed Products'),
            'row' => $item,
            'categories' => $categories,
            'products' => $products,
            'section_products' => $section_products
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

        $tabbedProductService = new TabbedProductService();
        $tabbedProductService->create($request->toArray());

        return redirect()->route('admin.tabbed.products')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TabbedSection $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TabbedSection $item)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required_if:type,category',
            'products' => 'required_if:type,product|array|min:1',
        ]);

        $tabbedProductService = new TabbedProductService();
        $tabbedProductService->update($item, $request->toArray());

        return redirect()->route('admin.tabbed.products')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TabbedSection $item
     * @return \Illuminate\Http\Response
     */
    public function delete(TabbedSection $item)
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
        $section = TabbedSection::findOrFail($request->id);
        $section->status = $request->value;
        $section->save();
    }
}
