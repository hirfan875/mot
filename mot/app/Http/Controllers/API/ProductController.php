<?php

namespace App\Http\Controllers\API;

use App\Extensions\Response;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CouponResource;
use App\Http\Resources\FlashDealResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\RecentViewedResource;
use App\Http\Resources\ReviewResource;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\FlashDeal;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\RecentViewedProducts;
use App\Models\Store;
use App\Models\TabbedSection;
use App\Models\TrendingProduct;
use App\Service\CategoryService;
use App\Service\CouponService;
use App\Service\FilterCategoryService;
use App\Service\FilterProductsService;
use App\Service\FlashDealService;
use App\Service\ProductService;
use App\Service\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AllProductResource;

class ProductController extends BaseController
{
    /**
     * @param $category_slug
     * @param Request $request
     * @param FilterProductsService $productService
     * @return \Illuminate\Http\Response
     */
    public function index($category_slug = null, Request $request, FilterProductsService $productService)
    {

        $category_slug = $request->slug;
        $productService->setContext($productService::LIST_MODE_CATEGORY)->setActiveFilter();
        /** get category ID using provided category slug then filter using category id */
        if ($category_slug != null) {
            $request->category_slug = $category_slug;
            $category = Category::select('id', 'title', 'meta_title', 'meta_desc', 'meta_keyword', 'image', 'banner')->where('slug', $category_slug)->first();
            $request->category = $category;
            if ($category != null) {
                $productService->byCategory($category->id);
            }
        }
        $view_data = $this->renderFilter($request, $productService);

        return $this->sendResponse($view_data, __('Data loaded successfully'));
    }

    /**
     * @param $request
     * @param FilterProductsService $productService
     * @return array
     */
    public function renderFilter($request, FilterProductsService $productService)
    {
        $currency = getCurrency();
        $categoryService = new CategoryService();
        $categoryId = (int)$request->get('category_id');
        $per_page = isset($request->per_page) ? $request->per_page : 15;
        $categories = $categoryService->getTopLevelCategories();
        $categoryIds = $request->get('categories');
        $min_price = $productService->getMinPrice();
        $max_price = $productService->getMaxPrice();

        $base_query = $productService;
        
        if (isset($request->type) && $request->type == 'new_arrival') {
            
            $base_query->newArrival();
        }
        if (isset($request->type) && $request->type == 'under_one') {
            $currencyAmount = currencyInTRY($request->currency_code,1);
            $base_query->underOnePrice($currencyAmount);
        }
        if (isset($request->trending_section_id)) {
            $trendingProduct = TrendingProduct::findOrFail($request->trending_section_id);
            $base_query->byTrendingProductSection($trendingProduct);
        }
        if (isset($request->tabbed_section_id)) {
            $tabbedProduct = TabbedSection::findOrFail($request->tabbed_section_id);
            $base_query->byTabbedProductSection($tabbedProduct);
        }
        /* filter by brands */
        if (isset($request->brands)) {
            $brandIds = Brand::whereIn('slug', $request->brands)->pluck('id')->toArray();
            $base_query->byBrand($brandIds);
        }

        /* filter by price range */
        if (($request->min_price > 0 && $request->max_price > 0)) {
            $base_query->byPriceRange($request->min_price, $request->max_price);
        }

        /* filter by minimum price */
        if (($request->min_price > 0)) {
            $base_query->byMinPrice($request->min_price);
        }

        /* filter by maximum price */
        if (($request->max_price > 0)) {
            $base_query->byMaxPrice($request->max_price);
        }

        /* filter by keyword */
        if ($request->keyword) {
            $base_query->byKeywordSearch(trim($request->keyword), true);

            $data = array();
            if (isset($_SESSION['key'])) {
                $data[] = $_SESSION['key'];
            }
            if (count($data) > 0) {
                $merge = array_merge($_SESSION['key'], [$request->keyword]);
            } else {
                $merge = array_merge($data, [$request->keyword]);
            }
            $merge = array_unique($merge);
            $_SESSION['key'] = $merge;
        }

        /* filter by attributes */
        if ($request->variants != null) {
            $attributeIds = Attribute::whereIn('slug', $request->variants)->pluck('id')->toArray();
            $base_query->byAttributes($attributeIds);
        }

        /* filter by category id */
        if ($categoryId) {
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

        $min_price = $base_query->get()->min('promo_price');
        $max_price = $base_query->get()->max('promo_price');

        $products = $base_query->paginate($per_page);
        
//                dd($base_query->getQueries());

        /* get categories related to trending products */
        if ($request->category_slug != null) {
            $slugCategory = $categoryService->getBySlug($request->category_slug);
            if(isset($slugCategory->id)){
                $categories = $categoryService->getSubCategories($slugCategory->id);
            }
        } elseif ($categoryId) {
            $categories = $categoryService->getSubCategories($categoryId);
        }elseif ($categoryIds) {
            $categories = $categoryService->getSubCategoriesByIds($categoryIds);
        }else {
            $productsIds = $all_products->pluck('id')->toArray();
            $categories = $categoryService->getTopLevelByProductIds($productsIds);
        }

        $top_parent_category = null;
        if (isset($request->category)) {
            $top_parent_category = $categoryService->findTopParent($request->category->id);
        }
        $category_title= null;
        if(isset($request->category)){
            $category_title = isset($request->category->category_translates) ? $request->category->category_translates->title : $request->category->title;
        }
        
        $view_data = [
            // 'keyword'               => $keyword,
            'selected_category_id' => $categoryId,
            'products' => ProductResource::collection($products)->response()->getData(true),
            /* fiters parameters */
            'categories' => CategoryResource::collection($categories),
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
//            'category_title' => $category_title,
            'currency' => $currency,
            'top_parent_category' => $top_parent_category
        ];

        return $view_data;
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

    /**
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $couponService = new CouponService();
        $product = Product::find($id);
        $flashDeal = null;
        if($product){
        $flashDeal = $this->getFlashDealById($product);
        }
        if (!$product) {
            return $this->sendError(__('Product not found'), null);
        }
        $product->getRelations(['gallery', 'store', 'variations.variation_attributes', 'product_translates']);
        // get category IDS
        $product_categories_ids = $product->categories->pluck('id')->toArray();
        $similar_products = $this->getSimilarProducts($product_categories_ids, $product->id);

        $productData = [];
        $productData['id'] = $product->id;
        $productData['store_id'] = $product->store_id;
        $productData['name'] = $product->product_translates ? $product->product_translates->title : $product->title;
        $productData['type'] = $product->type;
        $productData['slug'] = $product->slug;
        $productData['image'] = $product->image_url;
        $productData['sku'] = $product->sku;
        $productData['seller_name'] = $product->store->store_profile_translates ? $product->store->store_profile_translates->name : $product->store->name;
        $productData['seller_slug'] = $product->store->slug;
        $productData['stock'] = $product->getTotalStock() == null ? 0 : $product->getTotalStock();
        $productData['price'] = (double)$product->price;
        $productData['promo_price'] = (double)$product->promo_price;
        $productData['short_description'] = $product->product_translates ? $product->product_translates->short_description : $product->short_description;
        $productData['description'] = $product->product_translates ? $product->product_translates->data : $product->data;
        $productData['additional_information'] = $product->additional_information;
        $productData['total_ratings'] = $product->rating_count;
        $productData['average_rating'] = (double)$product->rating;
        $productData['is_wishlist'] = $product->IsWishlist();
        $productData['gallery'] = $product->gallery;
        $productData['reviews'] = ReviewResource::collection($product->approved_reviews);
        $productData['currency'] = getCurrency();
        $productData['is_variable'] = !$product->isSimple() && !$product->isBundle();
        $productData['is_sold_out'] = $product->soldOut();
        $productData['all_ratings'] = [
            $product->approved_reviews->where('rating', 1)->count(),
            $product->approved_reviews->where('rating', 2)->count(),
            $product->approved_reviews->where('rating', 3)->count(),
            $product->approved_reviews->where('rating', 4)->count(),
            $product->approved_reviews->where('rating', 5)->count(),
        ];
        $productData['is_flash_deal'] = false;
        $productData['deal_ending_at'] = 0;
        $productData['deal_end_date'] = null;
        if ($flashDeal != null) {
            $productData['is_flash_deal'] = true;
            $productData['deal_ending_at'] = $flashDeal->ending_at->isFuture() ? \Carbon\Carbon::parse($flashDeal->ending_at)->diffInSeconds() : 0;
            $productData['deal_end_date'] = $flashDeal->ending_at->isFuture() ? $flashDeal->ending_at : null;
            $productData['discount'] = $flashDeal->discount;
        }

        $attributes = [];
        $variations = [];
        if ($product->attributes->count() > 0) {
            /* create attribute array */
            $attrGroup = $product->attributes->groupBy('attribute_id');
            $key = 0;
            foreach($attrGroup as $group) {
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
                $attributes[$key]['title'] = $title;
                $attributes[$key]['type'] = $attr->type;
                $attributes[$key]['slug'] = $attr->slug;
                $attributes[$key]['options'] = $optionArray;

                $key++;
            }

            /* create variations array for matching attributes row */
            foreach ($product->variations as $varKey => $variation) {
                $variationOptions = ProductAttribute::where('variation_id', $variation->id)->get();
                $optionsSlug = Attribute::whereIn('id', $variationOptions->pluck('option_id'))->pluck('slug')->toArray();
                $variations[$varKey]['id'] = $variation->id;
                $variations[$varKey]['price'] = (double)$variation->price;
                $variations[$varKey]['promo_price'] = (double)$variation->promo_price;
                $variations[$varKey]['stock'] = (int)$variation->stock;
                $variations[$varKey]['image'] = $variation->image;
                $variations[$varKey]['slug_string'] = implode("-", $optionsSlug);
                $variations[$varKey]['slug_array'] = $optionsSlug;
            }
        }

        $productData['attributes'] = $attributes;
        $productData['variations'] = $variations;
        $productData['similar_products'] = ProductResource::collection($similar_products);
        $productData['coupons'] = CouponResource::collection($couponService->getByProductId($product)->whereNotNull('coupon_code'));
        $productData['is_able_to_review'] = false;
        $productData['order_id'] = 0;

        if (Auth('sanctum')->check()) {
            $customer = Auth('sanctum')->user();
            
            if ($customer != null) {
                $this->addToRecentViewed($product->id, $customer->id);
                $productData['is_able_to_review'] = $this->isAbleToReview($product, $customer);
                $productData['order_id'] = $this->isAbleToReview($product, $customer) ? $this->getOrderItem($product, $customer)->id : 0;
            }
        }

        $data = $productData;
        return $this->sendResponse($data, __('Data loaded successfully'));
    }

    public function getCouponsByProId($id)
    {
        $couponService = new CouponService();
        $product = Product::find($id);
        $coupons = CouponResource::collection($couponService->getByProductId($product)->whereNotNull('coupon_code'));

        return $this->sendResponse($coupons, __('Data loaded successfully'));
    }


    /**
     * @param $product_categories_ids
     * @param $product_id
     * @return mixed
     */
    private function getSimilarProducts($product_categories_ids, $product_id)
    {
        return
            Product::with(['store', 'gallery', 'attributes', 'product_translates'])
                ->where('id', '!=', $product_id)
                ->whereHas('store', function ($query) {
                    $query->where('is_approved', true)->where('status', true);
                })
                ->whereHas('categories', function ($query) use ($product_categories_ids, $product_id) {
                    $query->whereIn('category_id', $product_categories_ids);
                })
                ->limit(12)
                ->active()
                ->get();
    }

    /**
     * @param FilterCategoryService $request
     * @return \Illuminate\Http\Response
     */
    public function getAllCategories(FilterCategoryService $request)
    {
//        $baseCategories = $request->setIncludeOnlyParentCategory(true)->withOnlyActiveProducts()->active();
        $baseCategories = $request->withSubcategories()->active();
        $categories = CategoryResource::collection($baseCategories->get());

        return $this->sendResponse($categories, __('Data loaded successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function productReviews(Request $request)
    {
        if (!Auth('sanctum')->check()) {
            $this->sendError(__('User not found'), []);
        }

        $validator = Validator::make($request->all(), [
            'order_item_id' => 'numeric|required',
            'rating' => 'required|numeric',
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        try {
            $reviewService = new ReviewService();
            $customer = \Auth('sanctum')->user();
            $lang = getCurrentLang();
            $data = [
                'language_id' => $lang != null ? $lang->id : null,
                'order_item_id' => $request->order_item_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'gallery' => $request->gallery
            ];

            $review = $reviewService->createProductReviewByCustomer($customer, $data);
        } catch (\Exception $exc) {
            return $this->sendError(__('Something went wrong'), __($exc->getMessage()));
        }

        return $this->sendResponse($review, __('Your feedback has been submitted successfully!'));
    }

    /**
     * @param FlashDealService $flashDealService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function flashDeals(FlashDealService $flashDealService)
    {
        $flashDealsProducts = $flashDealService->getAllDeals(20);
        $flashDealsData = [];
        $flashDealsData['products'] = FlashDealResource::collection($flashDealsProducts);
        $flashDealsData['currency'] = getCurrency();

        return $this->sendResponse($flashDealsData, __('Data loaded successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getAllProducts(Request $request)
    {
        $baseQuery = Product::active()->whereNull('parent_id');
        $baseQuery->whereHas('product_translates', function ($q) use ($request) {
            $q->where('language_code', $request->lang);
        });
        if ($request->category_id) {
            $baseQuery->whereHas('categories', function ($query) use ($request) {
                $queryy>where('category_id', $request->category_id);
            });
        }
        $baseQuery->with(['product_translates']);
        $baseQuery->select('id', 'title', 'slug');
        $products = $baseQuery->get();
        return $this->sendResponse(AllProductResource::collection($products), __('Data loaded successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function compareProducts(Request $request)
    {
        try {
            $compareProducts = $request->products; /*[4075, 659, 9031]*/;

            if (empty($compareProducts)) {
                return $this->sendError('No product found');
            }

            $productService = new ProductService();
            $products = $productService->getCompareProducts($compareProducts);

            $productsData = [];
            foreach ($products as $key => $product) {
                $attributes = [];
                $productsData[$key]['id'] = $product->id;
                $productsData[$key]['name'] = $product->product_translates ? $product->product_translates->title : $product->title;
                $productsData[$key]['seller_name'] = $product->store != null ? isset($product->store->store_profile_translates) ? $product->store->store_profile_translates->name : $product->store->name : null;
                $productsData[$key]['price'] = (double)$product->price;
                $productsData[$key]['promo_price'] = (double)$product->promo_price;
                $productsData[$key]['image'] = $product->image;
                $productsData[$key]['stock'] = $product->stock;
                $productsData[$key]['average_rating'] = $product->rating;
                $productsData[$key]['total_ratings'] = $product->rating_count;
                $productsData[$key]['is_wishlist'] = $product->IsWishlist();
                $productsData[$key]['is_variable'] = !$product->isSimple();

                if ($product->attributes->count() > 0) {
                    $attrGroup = $product->attributes->groupBy('attribute_id');
                    foreach ($attrGroup as $group) {
                        $attr = Attribute::whereIn('id', $group->pluck('attribute_id'))->first();
                        $options = Attribute::with('attribute_translates')->whereIn('id', $group->pluck('option_id'))->get();
                        $optionArray = [];
                        foreach ($options as $option) {
                            isset($option->attribute_translates) ? array_push($optionArray, $option->attribute_translates->title) : array_push($optionArray, $option->title);
                        }
                        $title = isset($attr->attribute_translates) ? $attr->attribute_translates->title : $attr->title;
                        $attributes[$title] = $optionArray;
                    }
                }
                $productsData[$key]['attributes'] = $attributes;
            }

            return $this->sendResponse($productsData, __('Data loaded successfully'));
        } catch (\Exception $exc) {
            return $this->sendError(__($exc->getMessage()));
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function recentViewed()
    {
        if (!Auth('sanctum')->check()) {
            $this->sendError(__('User not found'), []);
        }
        try {
            $recentViewedProducts = RecentViewedProducts::with('product')->where('customer_id', Auth('sanctum')->user()->id)->orderBy('id', 'desc')->get();
            $products = array();
            if(isset($recentViewedProducts)){
                $products = RecentViewedResource::collection($recentViewedProducts);
            }
        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }
        return $this->sendResponse($products, __('Data loaded successfully'));
    }

    /**
     * @param $productId
     * @param $customerId
     * @return RecentViewedProducts
     */
    private function addToRecentViewed($productId, $customerId)
    {
        $recentViewed = RecentViewedProducts::where(['customer_id' => $customerId, 'product_id' => $productId])->first();
        if ($recentViewed == null) {
            $recentViewed = new RecentViewedProducts();
            $recentViewed->customer_id = $customerId;
            $recentViewed->product_id = $productId;
            $recentViewed->save();
        }
        return $recentViewed;
    }

    /**
     * @param Product $product
     * @return FlashDeal|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    private function getFlashDealById(Product $product)
    {
        $flashDeal = FlashDeal::query()
            ->whereIsApproved(true)
            ->whereStatus(true)
//            ->where('starting_at', '<=', now())
//            ->where('ending_at', '>=', now())
            ->with('product')
            ->wherehas('product', function ($query) use ($product) {
                $query->where('id', $product->id);
            })->whereHas('store', function ($query) {
                $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
            })->first();

        return $flashDeal;
    }

    /**
     * @param Product $product
     * @param $customer
     * @return bool
     */
    private function isAbleToReview(Product $product, Customer $customer)
    {
        $orderItem = $this->getOrderItem($product, $customer);

        if ($orderItem == null) {
            return false;
        }

        $reviews = $product->reviews()->where('customer_id', $customer->id)->get();

        if ($reviews->count() > 0) {
            return false;
        }

        return true;
    }

    /**
     * @param Product $product
     * @param Customer $customer
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getOrderItem(Product $product, Customer $customer)
    {
        $order = Order::with('order_items')->whereHas('order_items', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })->where('customer_id', $customer->id)->first();

        return $order;
    }
}
