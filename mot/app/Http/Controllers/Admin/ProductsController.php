<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Service\CategoryService;
use App\Service\StoreService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Store;
use App\Rules\Sku;
use App\Service\FilterCategoryService;
use App\Service\FilterTagsService;
use App\Service\MotFeeService;
use App\Service\ProductService;
use App\Service\ProductGalleryService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SampleProducts;
use App\Exports\StoreProducts;
use App\Models\ProductAttribute;
use App\Models\Category;

class ProductsController extends Controller
{
    /** @var \Monolog\Logger */
    private $logger;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->logger = getLogger('Product Controller');
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','store']]);
        $this->middleware('permission:product-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $storeService = new StoreService();
        $categoryService = new CategoryService();
        $baseQuery = Product::with('brand', 'tags')->whereNull('parent_id');

        if($request->categories) {
            $baseQuery = $baseQuery->whereHas('categories', function($query) use($request){
                $query->where('category_id', $request->categories);
            });
        }

        if($request->keyword) {
            $keyWord = $request->keyword;
            $baseQuery = $baseQuery->where('title', 'like', '%'.$keyWord.'%')->Orwhere('sku', '=', $keyWord);
        }

        if($request->store_id) {
            $baseQuery = $baseQuery->where('store_id', $request->store_id);
        }

        if($request->type) {
            $baseQuery = $baseQuery->where('type', $request->type);
        }

        $records = $baseQuery->latest()->paginate(15);
//        $filterTagsService = new FilterTagsService();
//        $tags = $filterTagsService->forSeller()->get();

        return view('admin.products.index', [
            'title' => __('Products'),
            'records' => $records,
            'stores' => $storeService->getAll(),
            'categories' => Category::where('status', true)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FilterCategoryService $filterCategoryService)
    {
        $categories = $filterCategoryService->withSubcategories()->active()->get();
        $attributes = Attribute::whereNull('parent_id')->with('options')->get();
        $stores = Store::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('sort_order', 'asc')->get();
        $filterTagsService = new FilterTagsService();
        $tags = $filterTagsService->get();
        $bundle_products = old('bundle_products', []);
        $bundle_products = Product::active()->where('type', 'simple')->get();

        return view('admin.products.add', [
            'title' => __('Add Product'),
            'section_title' => __('Products'),
            'categories' => $categories,
            'attributes' => $attributes,
            'stores' => $stores,
            'brands' => $brands,
            'tags' => $tags,
            'bundle_products' => $bundle_products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, FilterCategoryService $filterCategoryService, MotFeeService $motFeeService)
    {
        $product->load(['categories', 'gallery', 'tags', 'product_translate', 'bundle_products', 'attributes', 'variations.variation_attributes.option']);
        $categories = $filterCategoryService->withSubcategories()->active()->get();
        $attributes = Attribute::whereNull('parent_id')->with('options')->get();
        $stores = Store::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('sort_order', 'asc')->get();
        $filterTagsService = new FilterTagsService();
        $tags = $filterTagsService->get();
        $bundle_products = Product::active()->where('type', 'simple')->get();
        $product_bundle_ids = $product->bundle_products->pluck('id')->toArray();

        // set product data variables
        $product_categories_ids = $product->categories->pluck('id')->toArray();
        $product_attributes_ids = $product->attributes->pluck('attribute_id')->unique()->toArray();
        $product_attributes_optoins_ids = $product->attributes->pluck('option_id')->toArray();
        $product_attributes = $attributes->whereIn('id', $product_attributes_ids);
        $product_tags_ids = $product->tags->pluck('id')->toArray();

        // get product commission
        $mot_commission = $motFeeService->getProductCommission($product);
        $mot_commission_amount = $motFeeService->getCommissionAmount($product->discounted_price, $mot_commission);

        return view('admin.products.edit', [
            'title' => __('Edit Product'),
            'section_title' => __('Products'),
            'row' => $product,
            'categories' => $categories,
            'attributes' => $attributes,
            'stores' => $stores,
            'brands' => $brands,
            'tags' => $tags,
            'bundle_products' => $bundle_products,
            'product_categories_ids' => $product_categories_ids,
            'product_attributes_ids' => $product_attributes_ids,
            'product_attributes_optoins_ids' => $product_attributes_optoins_ids,
            'product_attributes' => $product_attributes,
            'product_tags_ids' => $product_tags_ids,
            'product_bundle_ids' => $product_bundle_ids,
            'mot_commission' => $mot_commission,
            'mot_commission_amount' => $mot_commission_amount
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
            'title' => 'required|max:200',
//            'brand' => 'required_unless:type,bundle',
            'store' => 'required_unless:type,bundle',
            'sku' => ['required', new Sku($request->store)],
            'price' => 'required',
            'categories' => 'required|array|min:1',
            'discount' => 'required_with:discount_type',
            'discount_type' => 'required_with:discount',
            'bundle_products' => 'required_if:type,bundle|array|min:1',
            'attributes' => 'required_if:type,variable|array|min:1',
            'variations' => 'required_if:type,variable|array|min:1',
            'image' => 'sometimes|mimes:jpg,jpeg,png'
        ]);

        $productService = new ProductService();
        $productService->create($request->toArray());

        return redirect()->route('admin.products')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|max:200',
//            'brand' => 'required_unless:type,bundle',
            'store' => 'required_unless:type,bundle',
            'sku' => ['required', new Sku($request->store, $product->id)],
            'price' => 'required',
            'slug' => 'required',
            'categories' => 'required|array|min:1',
            'discount' => 'required_with:discount_type',
            'discount_type' => 'required_with:discount',
            'bundle_products' => 'required_if:type,bundle|array|min:1',
            'attributes' => 'required_if:type,variable|array|min:1',
            'variations' => 'required_if:type,variable|array|min:1',
            'image' => 'sometimes|mimes:jpg,jpeg,png'
        ]);

        $productService = new ProductService();
        $productService->update($product, $request->toArray());

        return redirect()->route('admin.products')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function delete(Product $product)
    {
        $productService = new ProductService();
        $productService->delete($product);

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
        $product = Product::with('variations')->findOrFail($request->id);
        if ($product->variations->count() > 0) {
            foreach ($product->variations as $variation) {
                $variation->status = $request->value;
                $variation->save();
            }
        }
        $product->status = $request->value;
        $product->save();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts(Request $request)
    {
        $products = Product::active()->where('type', 'simple')->where('store_id', $request['store_id'] )->get();
        return response()->json(['products' => $products, 'success' => true]);
    }

    /**
     * upload media gallery
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function galleryUpload(Request $request)
    {
        $productGalleryService = new ProductGalleryService();
        $response = $productGalleryService->upload($request->toArray());

        return response()->json($response);
    }

    /**
     * delete media gallery file
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function galleryDelete(Request $request)
    {
        $productGalleryService = new ProductGalleryService();
        $response = $productGalleryService->delete($request->toArray());

        return response()->json($response);
    }

    /**
     * update media gallery sorting order
     *
     * @param Request $request
     * @return void
     */
    public function galleryUpdateOrder(Request $request)
    {
        $productGalleryService = new ProductGalleryService();
        $productGalleryService->updateSortingOrder($request->toArray());
    }

    /**
     * approve seller product
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function approve(Product $product)
    {
        $product->is_approved = true;
        $product->save();

        return back()->with('success', __('Product approved successfully.'));
    }

    /**
     * approve seller product
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function approveAll(Request $request)
    {
        if(isset($request->ids)){
            foreach ($request->ids as $val){
                $product = Product::find($val);
                $product->is_approved = true;
                $product->save();
            }
        }
//        dd($product);
        return back()->with('success', __('Product approved successfully.'));
    }

    /**
     * approve seller product
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function statusAll(Request $request)
    {
        if(isset($request->ids)){
            foreach ($request->ids as $val){
                $product = Product::find($val);
                $product->status = true;
                $product->save();
            }
        }
        return back()->with('success', __('Product Status update successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request)
    {

         if(isset($request->ids)){
            foreach ($request->ids as $val){
                $product = Product::find($val);
                $productService = new ProductService();
                $productService->delete($product);
            }
        }
        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
     public function import(Request $request)
     {
         try {
             $request->validate([
                 'store_id' => 'required',
                 'products-excel-sheet' => 'required|mimes:xlsx,xlsm',
             ]);

             $storeService = new StoreService();
             $store = $storeService->getById($request->store_id);
             $excelFile = $request->file('products-excel-sheet');

             $productImport = new ProductsImport($store);
             $productImport->import($excelFile);

         } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             // each failure is an instance of Failure
//             foreach ($failures as $failure) {
//                 $failure->row(); // row that went wrong
//                 $failure->attribute(); // either heading key or column index
//                 $failure->errors(); // Actual error messages
//                 $failure->values(); // The values of the row that has failed.
//             }
             return back()->withFailures($failures);
         } catch(\Error $er){
             return back()->withFailures($er->getMessage());
         }
         return redirect()->back()->with('success', __('Record has been imported successfully'));
     }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
     public function downloadSampleExcel(Request $request)
     {
         try {
//             $filenameExport = "sample-products.xlsx";
             $filenameExport = "sample-products.xlsm";
             if(isset($request->store_id)) {
                 $store = Store::find($request->store_id);
                 return Excel::download(new StoreProducts($store), $store->slug.'-products.xlsm');
             }
             return Excel::download(new SampleProducts(), $filenameExport);
         } catch (Exception $e) {
             return redirect()->back()->with('error', $e->getMessage());
         }
     }

    public function importImagesZip(Request $request)
    {
        $request->validate([
            'products-images' => 'required|mimes:zip',
        ]);

        if ($request->hasFile('products-images')) {
            $file = $request->file('products-images');

            $za = new \ZipArchive();
            $imagesZip = $file->getClientOriginalName();
//            dd($file->getPathname());
//            dd($file->getRealPath());
            $za->open($file->getRealPath());
            $za->extractTo(public_path('storage/imports'));
            $za->close();

            if($za->open($file->getRealPath() == true)){
                return redirect()->back()->with('success', 'Images has been unzipped');
            }
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function deleteGarbage()
    {
        $products = Product::whereNotNull('parent_id')->get();
        foreach ($products as $product) {
            $attributes = ProductAttribute::where('variation_id', $product->id)->get();
            if ($attributes->count() == 0) {
                $product->delete();
            }
        }
        return response()->json(['success' => true, 'message' => 'Garbage rows has been deleted successfully!']);
    }
}
