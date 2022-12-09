<?php

namespace App\Http\Controllers\Customer;

use App\Extensions\Response;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\TabbedSection;
use App\Models\TrendingProduct;
use App\Models\Wishlist;
use App\Service\CategoryService;
use App\Service\WishlistService;
use App\Service\FilterProductsService;
use App\Service\BrandService;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Currency;
use App\Models\ProductAttribute;
use App\Models\Store;
use App\Service\FilterCategoryService;
use App\Service\DailyDealService;
use App\Service\FlashDealService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\CountValidator\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Cookie;
session_start();

class ProductController extends Controller
{
    public function index($category_slug = null, Request $request, FilterProductsService $productService)
    {
        $productService
            ->setContext($productService::LIST_MODE_CATEGORY)
            ->setActiveFilter();
        $meta_title =  '';
        $meta_description = '';
        $meta_keyword = '';

        /** get category ID using provided category slug then filter using category id */
        if($category_slug != null) {
            $request->category_slug = $category_slug;
            $category = Category::select('id','title','meta_title','meta_desc','meta_keyword', 'image', 'banner')->where('slug', $category_slug)->first();
            $request->category = $category;
            $request->breadcrumbs = isset($category) ? isset($category->category_translates) ? $category->category_translates->title : $category->title : '';
            if($category != null){
                $productService->byCategory($category->id);
            }
            if($request->category){
            $meta_title = isset($request->category->category_translates->meta_title) ? $request->category->category_translates->meta_title : $request->category->meta_title;
            $meta_description = isset($request->category->category_translates->meta_desc) ? $request->category->category_translates->meta_desc : $request->category->meta_desc;
            $meta_keyword = isset($request->category->category_translates->meta_keyword) ? $request->category->category_translates->meta_keyword : $request->category->meta_keyword;
            }

            }


        $view_data = $this->renderFilter($request, $productService, $meta_title, $meta_description, $meta_keyword);

        return view('web.products.index', $view_data);
    }

    /**
     * @param string $keyword
     * @param Request $request
     * @param FilterProductsService $productService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request, FilterProductsService $productService)
    {
        $keyword = $request->get('keyword');
        $productService->relations(['store', 'gallery'])->byKeywordSearch(trim($keyword), true)->setActiveFilter();
        $request->breadcrumbs = __('search');
        $meta_title = $request->breadcrumbs;

         $data=array();
        if(isset($_SESSION['key'])){
        $data[] = $_SESSION['key'];
        }
        if(count($data) > 0){
            $merge = array_merge($_SESSION['key'], [$keyword]);
        } else {
            $merge = array_merge($data, [$keyword]);
        }
        $merge= array_unique($merge);
        $_SESSION['key'] = $merge;

        $view_data = $this->renderFilter($request, $productService, $meta_title);

        return view('web.products.index', $view_data);
    }

    public function getProducts()
    {
        $getProducts = Product::select('title')->whereNotNull('title')->where('status', 1)->whereNull('parent_id')->where('is_approved', true)
                ->whereHas('store', function ($query) {
                    $query->where('is_approved', true)->where('status', true);
                })->get();
        return response()->json($getProducts);
    }


    /**
     * TODO It is clear that most of these methods can be combined ..
     */
    /**
     * @param string $keyword
     * @param Request $request
     * @param FilterProductsService $productService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function trending(Request $request, TrendingProduct $trendingProduct, FilterProductsService $productService)
    {
        $productService->relations(['store', 'gallery'])->byTrendingProductSection($trendingProduct)->setContext($productService::LIST_MODE_TRENDING_SECTION)->setActiveFilter();
        $request->breadcrumbs =$trendingProduct->title;
        $request->trending_products = true;
        $view_data = $this->renderFilter($request, $productService);

        return view('web.products.index', $view_data);
    }

    public function tabbed(Request $request, TabbedSection $tabbedProduct, FilterProductsService $productService)
    {
        $productService
            ->relations(['store', 'gallery'])
            ->byTabbedProductSection($tabbedProduct)
            ->setContext($productService::LIST_MODE_TAB_SECTION)
            ->setActiveFilter();
        $request->breadcrumbs = $tabbedProduct->title;
        $view_data = $this->renderFilter($request, $productService);
        return view('web.products.index', $view_data);
    }

    /**
     * @param Request $request
     * @param FilterProductsService $productService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function newArrival(Request $request,FilterProductsService $productService)
    {
        $productService->relations(['store', 'gallery'])->newArrival()->setActiveFilter();
        $request->breadcrumbs = __('New Arrivals');
        $view_data = $this->renderFilter($request, $productService);

        return view('web.products.index', $view_data);
    }
    
    /**
     * @param Request $request
     * @param FilterProductsService $productService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function underOne(Request $request,FilterProductsService $productService)
    {
        
        $productService->relations(['store', 'gallery'])->underOnePrice(currencyInTRY(getCurrency()->code,1))->setActiveFilter()->latest();
        $request->breadcrumbs = __('One '.getCurrency()->code.' Sale');
        $view_data = $this->renderFilter($request, $productService);

        return view('web.products.index', $view_data);
    }

    /**
     * @param string $slug
     * @param Request $request
     * @param FilterProductsService $productService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function brand(string $slug, Request $request, FilterProductsService $productService, BrandService $brandService)
    {
        $brand = Brand::where('slug', trim($slug))->first();
        if (!$brand) {
            // TODO Irfan Find where is this caught and handled ?
            throw new ModelNotFoundException(__('Unable to Find brand with slug ') . $slug);
        }
        $productService->relations(['store', 'gallery'])
            ->byBrand($brand->id)
            ->setActiveFilter();

        $view_data = $this->renderFilter($request, $productService);
        return view('web.products.index', $view_data);
    }

    public function detail($slug, Product $product, Request $request)
    {
        if ($request->has('lang') && !empty($request->lang)) {
            app()->setLocale($request->lang);
        }

        if ($request->has('currency') && !empty($request->currency)) {
            $currency = Currency::where('code', strtoupper($request->currency))->first();
            setCurrency($currency);
        }

        $meta_title = isset($product->product_translates->meta_title) ? $product->product_translates->meta_title : $product->meta_title;
        $meta_description = isset($product->product_translates->meta_desc) ? $product->product_translates->meta_desc : $product->meta_desc;
        $meta_keyword = isset($product->product_translates->meta_keyword) ? $product->product_translates->meta_keyword : $product->meta_keyword;

        if($meta_title == ''){
            $meta_title = isset($product->product_translates->title) ? $product->product_translates->title : $product->title;
        }
        if($meta_description == ''){
           $meta_description = isset($product->product_translates->data) ? $product->product_translates->data : $product->data;
        }
        if($meta_keyword == ''){
            $meta_keyword = isset($product->product_translates->title) ? $product->product_translates->title : $product->title;
        }

//        $meta_title = $meta_title .__('| Turkish Products ') .__('| Made in Turkey');

        if (!$product->status) {
            throw new  NotFoundHttpException();
        }
        $product->getRelations(['gallery', 'store', 'categories', 'variations.variation_attributes', 'product_translates', 'brand']);
        $product->total_reviews = $product->reviews()->count();
        $product->variants = [];

        $review = $product->reviews()->where('language_id',getLocaleId(app()->getLocale()))->get();
        if($review->count() == 0){
            $review = $product->reviews()->get();
        }
        $attrTitle = array();
        if($product->variations->count() > 0) {
            $attributes = [];
            $variations = [];

            foreach ($product->variations as $product_variation) {
                //Get attributes of specific variant
                $variant_attr = $product_variation->variation_attributes->pluck('option_id')->toArray();
                $variant_attr = Attribute::whereIn('id', $variant_attr)->select('id', 'title', 'slug')->get()->toArray();

                $attr_names = array_column($variant_attr, 'title');
                $attr_slugs = array_column($variant_attr, 'slug');
                $temp_variation['id'] = $product_variation->id;
                $temp_variation['name'] = $product->title . ' ' . implode(' ', $attr_names);
                $temp_variation['price'] = currency_format($product_variation->price);
                $temp_variation['sku'] = $product_variation->sku;
                $temp_variation['stock'] = $product_variation->stock;
                $temp_variation['promo_price'] = currency_format($product_variation->promo_price);
                $temp_variation['attributes'] = $variant_attr;
                $temp_variation['options'] = $attr_slugs;
                $temp_variation['image'] = $product_variation->image;

                array_push($variations, $temp_variation);

                //Get All attributes with option values related to this product
                /*foreach($product_variation->variation_attributes as $key => $product_attributes)
                {
                    $attributeRow = Attribute::with('parent')->find($product_attributes->option_id);
                    if($attributeRow->has('parent'))
                    {
                        $options = ProductAttribute::where('attribute_id', $attributeRow->parent_id)->pluck('option_id')->toArray();
                        $options = Attribute::whereIn('id', $options)->select('id', 'title', 'slug', 'code')->get()->toArray();

                        $attributes[$key]['id'] = $attributeRow->parent->id;
                        $attributes[$key]['name'] = $attributeRow->parent->title;
                        $attributes[$key]['type'] = $attributeRow->parent->type;
                        $attributes[$key]['slug'] = $attributeRow->parent->slug;
                        $attributes[$key]['options'] = $options;
//                        $attributes[$key]['image'] = $product_variation->image;
                    }
                }*/
            }

            /* create attribute array */
            $attrGroup = $product->attributes->groupBy('attribute_id');
            $key = 0;
            foreach ($attrGroup as $group) {
                $attr = Attribute::whereIn('id', $group->pluck('attribute_id'))->first();
                $options = Attribute::with('attribute_translates')->whereIn('id', $group->pluck('option_id'))->get();
                $optionArray = [];
                foreach ($options as $optionKey => $option) {
                    $optionArray[$optionKey]['id'] = $option->id;
                    $optionArray[$optionKey]['title'] = isset($option->attribute_translates) ? $option->attribute_translates->title : $option->title;
                    $optionArray[$optionKey]['slug'] = $option->slug;
                    $optionArray[$optionKey]['code'] = $option->code;
                }
                $title = isset($attr->attribute_translates) ? $attr->attribute_translates->title : $attr->title;
                $attributes[$key]['id'] = $attr->id;
                $attributes[$key]['name'] = $title;
                $attributes[$key]['type'] = $attr->type;
                $attributes[$key]['slug'] = $attr->slug;
                $attributes[$key]['options'] = $optionArray;

                $key++;
            }

            $product->attributes = $attributes;
            $product->variants = $variations;
        }
        // get category IDS
        $product_categories_ids = $product->categories->pluck('id')->toArray();
        $similar_products = $this->getSimilarProducts($product_categories_ids, $product->id);
        $other_products = Product::with('store','product_translates')->where('status', true)->where('store_id', $product->store->id)->where('id', '!=', $product->id)->inRandomOrder()->take(4)->get();

        $socialShare = \Share::page(\URL::current(), $meta_title)
            ->facebook()
            ->twitter()
            ->whatsapp()
            ->getRawLinks();

    	return view('web.products.detail', ['product' => $product, 'review' => $review, 'reviews' => $product->reviews, 'store' => $product->store, 'similar_products' => $similar_products, 'other_products' => $other_products, 'meta_title' => $meta_title, 'meta_description' => $meta_description, 'meta_keyword' => $meta_keyword, 'currency' => getCurrency(), 'socialShare' => $socialShare]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function addToWishlist(Request $request,$id){
        try {
            $customer = Customer::whereId(Auth()->user()->id)->first();
            $wishlistService = new WishlistService();
            $wishlistService->add($customer, $id);
            $count = $wishlistService->count($customer);
            $product = Product::whereId($id)->first();
        } catch (\Exception $exc) {
            return Response::error('customer.account', __($exc->getMessage()), $exc, $request);
        }
        return Response::success('customer.account', [
            'message' => __('Product has been added to wishlist'),
            'totalCount' => $count,
            'product' => $product
        ], $request);

    }

    public function removeFromWishlist(Request $request,$productId)
    {

        try {
            $customer = Customer::findOrFail(Auth()->user()->id);

            $wishlistService = new WishlistService();
            $deleted = $wishlistService->remove($customer, $productId);
            if (!$deleted) {
                return Response::error('customer.account', __('Unable to Deleted'),[], $request, 400);
            }
            $count = $wishlistService->count($customer);

        } catch (\Exception $exc) {
            return Response::error('customer.account', $exc->getMessage(), $exc, $request, 501);
        }
        return Response::success('customer.account', [
            'message' => __('Item removed successfully'),
            'totalCount' => $count
        ], $request);


    }

    /**
     * @return \stdClass
     */
    public function getBanners(): \stdClass
    {
        /* banners dummy data */
        $top_banners = new \stdClass;
        $top_banner_image = array(
            ['id' => 1, 'image' => url('/') . '/assets/frontend/assets/img/productBanner1.jpg', 'url' => '#'],
            ['id' => 2, 'image' => url('/') . '/assets/frontend/assets/img/productBanner2.jpg', 'url' => '#']
        );
        return $top_banners;
    }

     /**
     * @param array $product_categories_ids
     * @param string $product_id
     * @return Product collection
     */
    private function getSimilarProducts($product_categories_ids, $product_id)
    {
        /**
         * @irfan .. please move to service and test
         */
        return
            Product::with(['store', 'gallery', 'attributes','product_translates'])
            ->where('id', '!=', $product_id)
            ->whereHas('store', function($query) {
                $query->where('is_approved',true)->where('status',true);
            })
            ->whereHas('categories', function($query) use($product_categories_ids, $product_id) {
                $query->whereIn('category_id', $product_categories_ids);
            })
            ->limit(12)
            ->active()
            ->get();
    }

    /**
     * @param Request $request
     * @return array|string[]
     */
    protected function sortBy(Request $request): array
    {
        $sort = [];

        if ($request->sort_by == 'new') {
            $sort = ['id' => 'desc'];
        }

        if ($request->sort_by == 'old') {
            $sort = ['id' => 'asc'];
        }

        if ($request->sort_by == 'price_high') {
            $sort = ['promo_price' => 'desc'];
        }

        if ($request->sort_by == 'price_low') {
            $sort = ['promo_price' => 'asc'];
        }
        return $sort;
    }

    public function renderFilter($request, FilterProductsService $productService , $meta_title = '', $meta_description = '', $meta_keyword = '')
    {
        $currency = getCurrency();
        $categoryService = new CategoryService();
        $categoryId     = (int) $request->get('category_id');
        $per_page       = isset($request->per_page) ? $request->per_page : 15;
        $categories     = $categoryService->getTopLevelCategories();
        $categoryIds    = $request->get('categories');
        $min_price      = $productService->getMinPrice();
        $max_price      = $productService->getMaxPrice();
//        $attributes     = $productService->getAttributes();
        $top_banners    = $this->getBanners();

        $base_query = $productService;

        if (isset($request->trending_product_section_id)){
            $trendingProduct = TrendingProduct::findOrFail($request->trending_product_section_id);
            $base_query->byTrendingProductSection($trendingProduct);
        }
        if (isset($request->tabbed_section_id)){
            $tabbedProduct = TabbedSection::findOrFail($request->tabbed_section_id);
            $base_query->byTabbedProductSection($tabbedProduct);
        }
        /* filter by brands */
        if(isset($request->brands)) {
            $brandIds = Brand::whereIn('slug', $request->brands)->pluck('id')->toArray();
            $base_query->byBrand($brandIds);
            $brand = Brand::whereIn('slug', $request->brands)->first();
            $meta_title = isset($brand->brand_translates->meta_title) ? $brand->brand_translates->meta_title : $brand->meta_title;
            $meta_description = isset($brand->brand_translates->meta_desc) ? $brand->brand_translates->meta_desc : $brand->meta_desc;
            $meta_keyword = isset($brand->brand_translates->meta_keyword) ? $brand->brand_translates->meta_keyword : $brand->meta_keyword;

        }
        /* filter by price range */
        if(($request->min_price > 0 && $request->max_price > 0)) {
            $base_query->byPriceRange($request->min_price, $request->max_price);
        }
        /* filter by minimum price */
        if(($request->min_price > 0)) {
            $base_query->byMinPrice($request->min_price);
        }
        /* filter by maximum price */
        if(($request->max_price > 0)) {
            $base_query->byMaxPrice($request->max_price);
        }
        /* filter by keyword */
        if ($request->keyword) {
            $base_query->byKeywordSearch(trim($request->keyword), true);
            $meta_title = __('Search');
            if($request->category_id > 0){
                $category = Category::select('id','title','meta_title','meta_desc','meta_keyword', 'image', 'banner')->where('id', $request->category_id)->first();
                $request->category = $category;
                $request->breadcrumbs = isset($category) ? isset($category->category_translates) ? $category->category_translates->title : $category->title : '';
            } else{
                $request->breadcrumbs =  'All Categories';
            }

            $data=array();
            if(isset($_SESSION['key'])){
            $data[] = $_SESSION['key'];
            }
            if(count($data) > 0){
                $merge = array_merge($_SESSION['key'], [$request->keyword]);
            } else {
                $merge = array_merge($data, [$request->keyword]);
            }
            $merge= array_unique($merge);
            $_SESSION['key'] = $merge;
        }

        /* filter by attributes */
        if($request->variants != null) {
            $attributeIds = Attribute::whereIn('slug', $request->variants)->pluck('id')->toArray();
            $base_query->byAttributes($attributeIds);
        }

        /* filter by category id */
        if ($categoryId) {
            $category     = $categoryService->getById($categoryId);
            $request->category = $category;
            $request->breadcrumbs = isset($category) ? isset($category->category_translates) ? $category->category_translates->title : $category->title : '';
            $productService->byCategory($categoryId);
        }
        /* filter by categories id */
        if ($categoryIds) {
            $productService->byCategory($categoryIds);
        }
        /* setup sorting array then pass it into filter service class */
        if (isset($request->sort_by)) {
            $sort = $this->sortBy($request);
            $base_query->sortBy($sort);
        }
        $brands = $base_query->getBrands();

        $all_products = $base_query->get();
        $attributes = $base_query->getAttributes($all_products);
        $products = $base_query->paginate($per_page);

//        dd($base_query->getQueries());

        /* get categories related to trending products */
        if ($request->category_slug != null) {
            $slugCategory = $categoryService->getBySlug($request->category_slug);
             $categories = $categoryService->getSubCategories(isset($slugCategory->id) ? $slugCategory->id : 0 );
        } elseif ($categoryId) {
            $categories = $categoryService->getSubCategories($categoryId);
        } else {
            $productsIds = $all_products->pluck('id')->toArray();
            $categories = $categoryService->getTopLevelByProductIds($productsIds);
        }

        /*$meta_title =
        $meta_description =
        $meta_keyword =*/

        $top_parent_category = null;
        if(isset($request->category)){
            $top_parent_category = $categoryService->findTopParent($request->category->id);
        }

        $view_data = [
            // 'keyword'               => $keyword,
            'selected_category_id' => $categoryId,
            'top_banners' => $top_banners,
            'products' => $products,
            /* fiters parameters */
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'filtered_brands' => isset($brandIds) ? $brandIds : [],
            'filtered_categories' => isset($categoryIds) ? $categoryIds : [],
            'filtered_attributes' => isset($attributeIds) ? $attributeIds : [],
            'per_page' => $per_page,
            'sort_by' => isset($sort_by) ? $sort_by : 'desc',
            'keyword' => $request->keyword,
            'category_id' => $request->category_id,
            'category' => isset($request->category) ? $request->category : null,
            'currency' => $currency,
            'breadcrumb' => isset($request->breadcrumbs) ? $request->breadcrumbs : null,
            'meta_title' => isset($meta_title) ? $meta_title : '',
            'meta_description' => isset($meta_description) ? $meta_description : '',
            'meta_keyword' => isset($meta_keyword) ? $meta_keyword : '',
            'top_parent_category' => $top_parent_category
        ];

        return $view_data;
    }

    public function dailyDeals(DailyDealService $dailyDealService)
    {
        $dailyDealsProducts = $dailyDealService->getAllDeals(20);

        return view('web.products.daily-deals', ['deals' => $dailyDealsProducts , 'currency'=> getCurrency()]);
    }


    public function flashDeals(FlashDealService $flashDealService)
    {
        $flashDealsProducts = $flashDealService->getAllDeals(20);

        return view('web.products.flash-deals', ['flashDeals' => $flashDealsProducts,'currency'=> getCurrency()]);
    }

}
