<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Brand;
use App\Rules\Sku;
use App\Service\FilterCategoryService;
use App\Service\FilterProductsService;
use App\Service\FilterTagsService;
use App\Service\MotFeeService;
use App\Service\ProductService;
use App\Service\ProductGalleryService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SampleProducts;
use App\Imports\ProductsImport;

class ProductsController extends Controller {

    /** @var \Monolog\Logger */
    private $logger;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:seller');
        $this->logger = getLogger('seller-controller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user = auth()->user();
        $filterProductsService = new FilterProductsService();
        $records = $filterProductsService->byStore($user->store_id)->relations(['brand', 'tags'])->latest()->get();
//        $filterTagsService = new FilterTagsService();
//        $tags = $filterTagsService->forSeller()->get();

        return view('seller.products.index', [
            'title' => __('Products'),
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FilterCategoryService $filterCategoryService) {
        $user = auth()->user();
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $attributes = Attribute::whereNull('parent_id')->with('options')->get();
        $brands = Brand::where('store_id', $user->store_id)->orWhere('store_id', null)->where('status', true)->orderBy('sort_order', 'asc')->get();
        $filterTagsService = new FilterTagsService();
        $tags = $filterTagsService->forSeller()->get();
        $bundle_products = old('bundle_products', []);
        $bundle_products = Product::active()->where('type', 'simple')->where('store_id', $user->store_id)->get();

        return view('seller.products.add', [
            'title' => __('Add Product'),
            'section_title' => __('Products'),
            'categories' => $categories,
            'attributes' => $attributes,
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
    public function edit(Product $product, FilterCategoryService $filterCategoryService, MotFeeService $motFeeService) {
        // authorize user
        $user = auth()->user();
        $this->authorize('canView', $product);
        $product->load(['categories', 'gallery', 'tags', 'attributes', 'bundle_products', 'variations.variation_attributes.option']);
        $categories = $filterCategoryService->active()->withSubcategories()->get();
        $attributes = Attribute::whereNull('parent_id')->with('options')->get();
        $brands = Brand::where('store_id', $user->store_id)->orWhere('store_id', null)->where('status', true)->orderBy('sort_order', 'asc')->get();
        $filterTagsService = new FilterTagsService();
        $tags = $filterTagsService->forSeller()->get();
        $bundle_products = Product::active()->where('type', 'simple')->where('store_id', $user->store_id)->get();
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

        return view('seller.products.edit', [
            'title' => __('Edit Product'),
            'section_title' => __('Products'),
            'row' => $product,
            'categories' => $categories,
            'attributes' => $attributes,
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
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|max:200',
//            'brand' => 'required_unless:type,bundle',
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
        $productService->create($request->toArray(), auth()->user());

        return redirect()->route('seller.products')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product) {
        // authorize user
        $this->authorize('canUpdate', $product);

        $request->validate([
            'title' => 'required|max:200',
//            'brand' => 'required_unless:type,bundle',
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
        $productService->update($product, $request->toArray(), auth()->user());

        return redirect()->route('seller.products')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function delete(Product $product) {
        // authorize user
        $this->authorize('canDelete', $product);

        $productService = new ProductService();
        $productService->delete($product);

        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request) {

        if (isset($request->ids)) {
            foreach ($request->ids as $val) {
                $product = Product::find($val);
                $productService = new ProductService();
                $productService->delete($product);
            }
        }
        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * update status
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request) {
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
     * upload media gallery
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function galleryUpload(Request $request) {
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
    public function galleryDelete(Request $request) {
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
    public function galleryUpdateOrder(Request $request) {
        $productGalleryService = new ProductGalleryService();
        $productGalleryService->updateSortingOrder($request->toArray());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request) {
        $user = auth()->user();
        try {
            $request->validate([
                'products-excel-sheet' => 'required|mimes:xlsx',
            ]);

            $excelFile = $request->file('products-excel-sheet');
            $productImport = new ProductsImport($user->store);
            $productImport->import($excelFile);

            return redirect()->back()->with('success', __('Record has been imported successfully'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return back()->withFailures($failures);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSampleExcel(Request $request) {
        try {
//            $filenameExport = "sample-products.xlsx";
            $filenameExport = "sample-products.xlsm";
            return Excel::download(new SampleProducts(), $filenameExport);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function importImagesZip(Request $request) {
        $request->validate([
            'products-images' => 'required|mimes:zip',
        ]);

        if ($request->hasFile('products-images')) {
            $file = $request->file('products-images');

            $za = new \ZipArchive();
            $imagesZip = $file->getClientOriginalName();
            $za->open($file->getRealPath());
            $za->extractTo(public_path('storage/imports'));
            $za->close();

            if ($za->open($file->getRealPath() == true)) {
                return redirect()->back()->with('success', 'Images has been unzipped');
            }
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function getTrendyolProducts(Request $request) {
        $user = auth()->user();
        if ($user) {
            $getStore = \App\Models\Store::where('id', $user->store_id)->first();
            $size = 50;
            $page = 0;

            $seller_id = $getStore->seller_id;

            if ($getStore->seller_id > 0 && $getStore->trendyol_approved == 1) {

                if ($request['page']) {
                    $page = $request['page'];
                }
                if ($request['size']) {
                    $size = $request['size'];
                }

                $headers = array(
                    'Content-Type:application/json',
                    'Authorization: Basic ' . base64_encode($getStore->trendyol_key . ":" . $getStore->trendyol_secret)
                );

                $requestUrl = "https://api.trendyol.com/sapigw/suppliers/" . $getStore->seller_id . "/products?approved=true&page=" . $page . "&size=" . $size;

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $requestUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => $headers,
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $response = json_decode($response);

                $products = $response->content;

                $productService = new ProductService();
                $data = [];
                foreach ($products as $key => $val) {

                    $getCategories = \App\Models\TrendyolCategories::with('categoriesAssign')->where('id', $val->pimCategoryId)->first();
                    $getProduct = \App\Models\Product::where('barcode', $val->barcode)->first();

                    if (!$getProduct) {
                        $data = [
                            'title' => $val->title,
                            'additional-brand' => $val->brandId,
                            'brandId' => $val->brandId,
                            'sku' => $productService->generateStoreSku($user->store_id, $val->productMainId),
                            'store_sku' => $val->productMainId,
                            'price' => $val->listPrice,
                            'promo_price' => $val->salePrice,
                            'categories' => isset($getCategories->categoriesAssign[0]) ? [$getCategories->categoriesAssign[0]->id] : '',
                            'createdBy' => 'trendyol-auto',
                            'type' => 'simple',
                            'stock' => $val->quantity,
                            'volume' => $val->dimensionalWeight,
                            'data' => $val->description,
                            'store' => $user->store_id,
                            'meta_title' => null,
                            'short_description' => null,
                            'images' => $val->images,
                            'barcode' => $val->barcode,
                            'vat_rate' => $val->vatRate,
//                        'cargo_company_id'=> $val->cargoCompanyId,
                            'stock_code' => $val->stockCode,
                            'status' => $val->onSale,
                            'additional_information' => $val->attributes,
                        ];

                        $productService->trendyolCreate($data, $user);
                    } else {
                        $data = [
                            'title' => $val->title,
                            'price' => $val->listPrice,
                            'promo_price' => $val->salePrice,
                            'stock' => $val->quantity,
                            'volume' => $val->dimensionalWeight,
                            'data' => $val->description,
                            'short_description' => null,
                            'images' => $val->images,
                            'barcode' => $val->barcode,
                            'vat_rate' => $val->vatRate,
                            'stock_code' => $val->stockCode,
                            'status' => $val->onSale,
                            'additional_information' => $val->attributes,
                        ];

                        $productService->trendyolUpdate($getProduct, $data, $user);
                    }
                }
                return $data;
            }
        }
    }

}
