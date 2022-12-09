<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Service\BrandService;
use App\Service\Media;
use App\Models\BrandTranslate;
use App\Models\Store;
use App\Service\StoreService;


class BrandsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:brands-list|brands-create|brands-edit|brands-delete', ['only' => ['index','store']]);
        $this->middleware('permission:brands-create', ['only' => ['create','store']]);
        $this->middleware('permission:brands-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:brands-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Brand::orderBy('sort_order', 'asc')->get();

        return view('admin.brands.index', [
            'title' => __('Brands'),
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
        $stores = Store::where('status',true)->orderBy('name', 'asc')->get();
        return view('admin.brands.add', [
            'title' => __('Add Brand'),
            'section_title' => __('Brands'),
            'stores' => $stores
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Brand $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $brand = $brand->with('brand_translate')->where('id', $brand->id)->first();
        $stores = Store::where('status',true)->orderBy('name', 'asc')->get();
        return view('admin.brands.edit', [
            'title' => __('Edit Brand'),
            'section_title' => __('Brands'),
            'row' => $brand,
            'stores' => $stores,
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
        ]);

        $brandService = new BrandService();
        $brandService->create($request->toArray());

        return redirect()->route('admin.brands')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Brand $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $brandService = new BrandService();
        $brandService->update($brand, $request->toArray());

        return redirect()->route('admin.brands')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Brand $brand
     * @return \Illuminate\Http\Response
     */
    public function delete(Brand $brand)
    {
        Media::delete($brand->image);
        $brand->delete();

        // decrement sort order
        Brand::where('sort_order', '>', $brand->sort_order)->decrement('sort_order');

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
        $brand = Brand::findOrFail($request->id);
        $brand->status = $request->value;
        $brand->save();
    }

    /**
     * Show list for changing sort order
     *
     * @return \Illuminate\Http\Response
     */
    public function sorting()
    {
        $brands = Brand::orderBy('sort_order', 'asc')->get();

        return view('admin.brands.sorting', [
            'title' => __('Sorting'),
            'section_title' => __('Brands'),
            'brands' => $brands
        ]);
    }

    /**
     * Update sorting order
     *
     * @param Request $request
     * @return void
     */
    public function updateSorting(Request $request)
    {
        foreach ( $request['items'] as $k=>$r ) {
            Brand::where('id', $r['id'])->update(['sort_order' => $r['order']]);
        }
    }

    /**
     * approve seller brand
     *
     * @param Brand $brand
     * @return \Illuminate\Http\Response
     */
    public function approve(Brand $brand)
    {
        $brand->is_approved = true;
        $brand->save();

        return back()->with('success', __('Brand approved successfully.'));
    }
}
