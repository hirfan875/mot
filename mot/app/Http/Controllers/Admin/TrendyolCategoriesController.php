<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrendyolCategories;
use App\Models\Category;
use App\Models\StoreStaff;
use Illuminate\Http\Request;
use App\Service\TrendyolCategoryService;
use App\Service\FilterCategoryService;
use App\Service\ProductService;
use App\Service\BrandService;
//use Auth;
use App\Rules\Sku;
use DB;
use Google\Cloud\Translate\TranslateClient;

class TrendyolCategoriesController extends Controller 
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth']);
//        $this->middleware('permission:translation-list|translation-create|translation-edit|translation-delete', ['only' => ['index','store']]);
//        $this->middleware('permission:translation-create', ['only' => ['create','store']]);
//        $this->middleware('permission:translation-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:translation-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        $baseQuery = TrendyolCategories::with(['childrenRecursive', 'categoriesAssign'])->whereNull('parent_id');

//        if ($request->keyword) {
//            $keyWord = $request->keyword;
//            $baseQuery = $baseQuery->where(function ($query) use ($keyWord) {
//                $query->where('title', 'like', '%' . $keyWord . '%')->Orwhere('id', 'like', '%' . $keyWord . '%');
//            });
//        }
        $records = $baseQuery->paginate(2);

        return view('admin.trendyol-categories.index', [
            'title' => __('Trendyol Categories'),
            'records' => $records,
        ]);
    }
    
     public function parentIndex(Request $request) 
     {
        $baseQuery = TrendyolCategories::with(['childrenRecursive', 'categoriesAssign']);

        $baseQuery = $baseQuery->whereHas('product' , function ($q) {
             $q->whereNotNull('trendyol_categories_id');
        });
        $records = $baseQuery->get(); 

        return view('admin.trendyol-categories.parent-index', [
            'title' => __('Trendyol Product Categories'),
            'records' => $records,
        ]);
    }
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.trendyol.com/sapigw-product/product-categories',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: __cfruid=73fe80cc08d503eef6cae877e0f266006de15779-1654152099; _cfuvid=tf867dUq0ffTPieFXxNtzPMhgvNLhSpKmMug.d3mK.w-1654152099291-0-604800000'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        foreach ($response->categories as $category) {

            $data = [
                'id' => $category->id,
                'title' => $category->name,
                'parent_id' => $category->parentId,
                'status' => true
            ];

            $trendyolCategoryService = new TrendyolCategoryService();
            $trendyolCategoryService->create($data);

            if (count($category->subCategories) > 0) {
                $this->store($category->subCategories);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($subcategories) 
    {
        foreach ($subcategories as $sub) {
            $data = [
                'id' => $sub->id,
                'title' => $sub->name,
                'parent_id' => $sub->parentId,
                'status' => true
            ];

            $trendyolCategoryService = new TrendyolCategoryService();
            $trendyolCategoryService->create($data);

            if (count($sub->subCategories) > 0) {
                $this->store($sub->subCategories);
            }
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createBrand() 
    {
        $headers = array(
            'Content-Type:application/json',
            'Authorization: Basic ' . base64_encode("xRcU3hFhw6T3KOMu9Gvf:nZTJkmAeR6j0kEpeKVkP") 
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.trendyol.com/sapigw/brands',
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
        foreach ($response->brands as $brands) {

            $data = [
                'id' => $brands->id,
                'title' => $brands->name,
                'status' => true
            ];
            
            $brandService = new BrandService();
            $brandService->createTrendyol($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrendyolCategories  $trendyolCategories
     * @return \Illuminate\Http\Response
     */
    public function assign($trendyol, Request $request, FilterCategoryService $filterCategoryService) 
    {
        $categories = $filterCategoryService->withSubcategories()->get();

        return view('admin.trendyol-categories.assign', [
            'title' => __('categories'),
            'categories' => $categories,
            'trendyol' => $trendyol
        ]);
    }

    public function update(Request $request, TrendyolCategories $trendyol) 
    {
        $categoriesAssign = DB::table('trendyol_categories_assign')->where('trendyol_categories_id',$request['trendyol_cat_id'])->first();
        if($categoriesAssign) {
            $result = DB::table('trendyol_categories_assign')->where('trendyol_categories_id', $request['trendyol_cat_id'])->update(['category_id' => $request['category_id']]);
        
        } else {
            $result = DB::table('trendyol_categories_assign')->insert(['trendyol_categories_id' => $request['trendyol_cat_id'],'category_id' => $request['category_id']]);
        
        }

        $records = TrendyolCategories::with('childrenRecursive')->whereNull('parent_id')->get();

        return redirect()->back()->with('success', 'Successfully done the operation.');
    }
    
    public function translate()
    {
        $translate = new TranslateClient(['key' => 'AIzaSyDfrr28mzao7KAh_t0s4caVn-_T6OcT7Rk']);

        // Translate text from english to french.
        $result = $translate->translate("Kadın 3'lü Pamuk Külot Karışık Düz Renk Likralı Kaşkorse Slip Külot", ['target' => 'en']);

        echo $result['text'] . "<br/>";

        // Detect the language of a string.
//        $result = $translate->detectLanguage('Hello world!');

//        echo $result['languageCode'] . "<br/>";

        // Get the languages supported for translation specifically for your target language.
//        $languages = $translate->localizedLanguages([ 'target' => 'en']);
        

    }
    
    
//    public function getTrendyolProducts() 
//    {
//
//
//        $headers = array(
//            'Content-Type:application/json',
//            'Authorization: Basic ' . base64_encode("xRcU3hFhw6T3KOMu9Gvf:nZTJkmAeR6j0kEpeKVkP") // <---
//        );
//        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'https://api.trendyol.com/sapigw/suppliers/106350/products?approved=true&page=200&size=10',
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'GET',
//            CURLOPT_HTTPHEADER => $headers,
//        ));
//
//        $response = curl_exec($curl);
//
//        curl_close($curl);
//        $response = json_decode($response);
////        dd($response);
//                
//        
////        $url = "http://www.google.co.in/intl/en_com/images/srpr/logo1w.png";
////        $contents = file_get_contents($url);
////        $name = substr($url, strrpos($url, '/') + 1);
////        Storage::put($name, $contents);
//
//        $products = $response->content;
//        $user = auth()->user();
//        
//        foreach($products as $key => $val){
//            $productService = new ProductService();
//            $data = [
//            'title' => $val->title,
//            'brand' => $val->brandId,
//            'sku' =>  $productService->generateStoreSku($user->store_id, $val->productCode),
//            'store_sku' => $val->productCode,
//            'price' => $val->salePrice,
//            'categories' => [$val->pimCategoryId],
//            'image' => 'sometimes|mimes:jpg,jpeg,png',
//            'createdBy' => 'trendyol-auto',
//            'type'=>'simple',
//            'stock' => $val->quantity,
//            'volume' => $val->dimensionalWeight,
//            'data' => $val->description,
//                
//        ];
//
//        
//        $productService->trendyolCreate($data, $user);
//        
//        }
//
//        
//
//        return redirect()->route('seller.products')->with('success', __('Record added successfully.'));
//    }

}
