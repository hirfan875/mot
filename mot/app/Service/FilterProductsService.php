<?php

namespace App\Service;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\TabbedSection;
use App\Models\TrendingProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use DB;

class FilterProductsService
{
    const LIST_MODE_NONE = 0;
    const LIST_MODE_CATEGORY = 1;
    const LIST_MODE_SEARCH = 2;
    const LIST_MODE_TAB_SECTION = 3;
    const LIST_MODE_TRENDING_SECTION = 4;

    protected int $listMode;
    /**
     * The base query builder instance.
     *
     * @var Builder
     */
    protected $query;

    protected $categories;
    protected $homePageSection;
    /**
     * exclude inactive stores products
     *
     * @var bool
     */
    protected $excludeInactiveStore;
    protected $excludeInactiveStoreAdded;

    /**
     * customer id for wishlist
     *
     * @var int
     */
    protected $customer_id;

    /** @var bool */
    protected $includeOnlyParentProduct;

    /** @var \Monolog\Logger $logger */
    protected $logger;

    /**
     * Create a new filter products instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->logger = getLogger('filter-product-service');
        $this->excludeInactiveStore = true;
        $this->includeOnlyParentProduct = true;
        $this->customer_id = 0;
        $this->excludeInactiveStoreAdded = false;
        $this->query = Product::query();
        $this->listMode = self::LIST_MODE_NONE;
    }

    /**
     * @param $categorySlug
     * @return $this
     */
    public function setContext($context)
    {
        $this->listMode = !is_null($context) ? $context : self::LIST_MODE_NONE;
        return $this;
    }

    /**
     * set exclude inactive stores filter
     *
     * @param bool $excludeInactiveStore
     * @return FilterProductsService
     */
    public function setExcludeInactiveStore(bool $excludeInactiveStore = false): FilterProductsService
    {
        $this->excludeInactiveStore = $excludeInactiveStore;
        return $this;
    }

    /**
     * set include only parent products
     *
     * @param bool $includeOnlyParentProduct
     * @return FilterProductsService
     */
    public function setIncludeOnlyParentProduct(bool $includeOnlyParentProduct = false): FilterProductsService
    {
        $this->includeOnlyParentProduct = $includeOnlyParentProduct;
        return $this;
    }

    /**
     * set customer id
     *
     * @param int $customer_id
     * @return FilterProductsService
     */
    public function setCustomerId(int $customer_id): FilterProductsService
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    /**
     * @param $productIds
     * @return $this
     */
    public function byIds($productIds): FilterProductsService
    {
        $this->query->whereIn('id', (array) $productIds);
        return $this;
    }
    /**
     * set active filter
     *
     * @return FilterProductsService
     */
    public function setActiveFilter(): FilterProductsService
    {
        $this->query->active();
        return $this;
    }

    public function withTranslates(): FilterProductsService
    {
        $this->query->with('product_translates');
        return $this;
    }

    /**
     * Search by Keywords
     *
     * @param $keyword
     * @param bool $include_meta
     * @return $this
     */
    public function byKeyword($keyword, bool $include_meta = false): FilterProductsService
    {
        $keyword = $this->fullTextWildcards($keyword);

        // TODO Pass $keyword as an argument to sql, it somehow did not worked
        if (!$include_meta) {
            $this->query->whereRaw(" MATCH (title) AGAINST  ('$keyword' in boolean mode) ");
            return $this;
        }
        $words = explode(' ', $keyword);
        $reservedSymbols = ['+', '*'];
        $keywords = str_replace($reservedSymbols, '', $words);
        $keywords = implode("','", $keywords);
        $this->query->with(['product_translates'])->whereHas('product_translates' , function (Builder $q) use ($keyword,$keywords) {
            return $q->whereRaw("( MATCH (title) AGAINST  (\"$keyword\" ) OR MATCH (meta_keyword) AGAINST  (\"$keyword\") )")
                ->orderByRaw("FIELD(title , '$keywords') ASC");
        });
        
        $this->query->orderByRaw("FIELD(title , '$keywords') ASC");
       
//            $this->query->sortBy(function ($result) use ($keywords) {
//                return strpos($result->title, $keywords);
//            });
         
        return $this;
    }
    
    public function byKeywordSearch($keyword, bool $include_meta = false): FilterProductsService
    {
        
        
        $searchTerm = $keyword;
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $searchTerm = str_replace($reservedSymbols, ' ', $searchTerm);

        $searchValues = preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);

//        $keyword = $this->fullTextWildcards($keyword);
//        
        // TODO Pass $keyword as an argument to sql, it somehow did not worked
        if (!$include_meta) {
            $this->query->whereRaw(" MATCH (title) AGAINST  ('$keyword' in boolean mode) ");
            return $this;
        }
        $words = explode(' ', $keyword);
        $reservedSymbols = ['+', '*'];
        $keywords = str_replace($reservedSymbols, '', $words);
        $keywords = implode("','", $keywords);

        $orderByRowCase = 'case when title LIKE "%' . $keyword . '%" then 1 ';
        foreach ($searchValues as $key1 => $regionSplitedText) {
            $key1 += 2;
            $orderByRowCase .= ' when title LIKE "%' . $regionSplitedText . '%" then ' . $key1 . '';
        }
        $key1 += 1;
        $orderByRowCase .= ' else ' . $key1 . ' end';

        $this->query->with(['product_translates'])->whereHas('product_translates', function (Builder $q) use ($keyword, $keywords, $searchValues) {
            $q->where('title', 'like', "%" . $keyword . "%");
            foreach ($searchValues as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }

//            $q->orderByRaw($orderByRowCase);
        });

        $this->query->orderByRaw($orderByRowCase);

        return $this;
    }

    /**
     * set category filter
     *
     * @param int|array $categories
     * @param bool $include_sub_cats_products
     * @return FilterProductsService
     */
    public function byCategory($categories, bool $include_sub_cats_products = false): FilterProductsService
    {
        $this->categories = $categories;
        // Please allow the following interface
        // $categories could be :
        // an int : id of a category
        // an array of int : array of category ids
        // a Category Object
        // a collection of Category Objects
        $categories = $this->getCategories($categories, $include_sub_cats_products);

        $this->query->whereHas('categories', function (Builder $query) use ($categories) {
            $query->whereIn('category_id', $categories);
        });
        return $this;
    }

    /**
     * set brand filter
     *
     * @param int|array $brands
     * @return FilterProductsService
     */
    public function byBrand($brands): FilterProductsService
    {
        if (!is_array($brands)) {
            $brands = [$brands];
        }

        $this->query->whereIn('brand_id', $brands);
        return $this;
    }

    /**
     * set store filter
     *
     * @param int|array $store
     * @return FilterProductsService
     */
    public function byStore(?int $store): FilterProductsService
    {
        if (!is_array($store)) {
            $store = [$store];
        }

        $this->query->whereIn('store_id', $store);
        return $this;
    }

    /**
     * set tag filter
     *
     * @param int|array $tags
     * @return FilterProductsService
     */
    public function byTag(?int $tags): FilterProductsService
    {
        if (!is_array($tags)) {
            $tags = [$tags];
        }

        $this->query->whereHas('tags', function (Builder $query) use ($tags) {
            $query->whereIn('tag_id', $tags);
        });
        return $this;
    }

    /**
     * set attributes filter
     *
     * @param int|array $attributes
     * @return FilterProductsService
     */
    public function byAttributes($attributes): FilterProductsService
    {
        if (!is_array($attributes)) {
            $attributes = [$attributes];
        }

        $this->query->whereHas('attributes', function (Builder $query) use ($attributes) {
            $query->whereIn('option_id', $attributes);
        });
        return $this;
    }

    /**
     * set slug filter
     *
     * @param string $slug
     * @return FilterProductsService
     */
    public function bySlug(string $slug): FilterProductsService
    {
        $this->query->whereSlug($slug);
        return $this;
    }

    /**
     * set sku filter
     *
     * @param string $sku
     * @return FilterProductsService
     */
    public function bySku(string $sku): FilterProductsService
    {
        return $this->query->whereSku($sku);
    }

    /**
     * set in stock filter
     *
     * @return FilterProductsService
     */
    public function inStock(): FilterProductsService
    {
        $this->query->where('stock', '>', 0);
        return $this;
    }

    /**
     * set out of stock filter
     *
     * @return FilterProductsService
     */
    public function outOfStock(): FilterProductsService
    {
        $this->query->where(function ($q) {
            $q->whereStock(0)->orWhereNull('stock');
        });
        return $this;
    }

    /**
     * set type filter
     *
     * @param string $type
     * @return FilterProductsService
     */
    public function byType(string $type): FilterProductsService
    {
        $this->query->whereType($type);
        return $this;
    }

    /**
     * set free delivery filter
     *
     * @return FilterProductsService
     */
    public function byFreeDelivery(): FilterProductsService
    {
        $this->query->whereFreeDelivery(true);
        return $this;
    }

    /**
     * exclude free delivery products filter
     *
     * @return FilterProductsService
     */
    public function exludeFreeDeliveryProducts(): FilterProductsService
    {
        $this->query->whereFreeDelivery(false);
        return $this;
    }

    /**
     * Get only those Products whose price fall between supplied values
     *
     * @param int $min
     * @param int $max
     * @return $this
     */
    public function byPriceRange(int $min, int $max): FilterProductsService
    {
        /**
         * This needs to be accounted for promotions
         * When promotions are build
         */
        $this->query->whereBetween('promo_price', [$min, $max]);
        return $this;
    }
    /**
     * Get only those Products whose price start with supplied values
     *
     * @param int $min
     * @return $this
     */
    public function byMinPrice(int $min): FilterProductsService
    {
        /**
         * This needs to be accounted for promotions
         * When promotions are build
         */
        $this->query->where('promo_price', '>=', $min);
        return $this;
    }
    /**
     * Get only those Products whose price end with supplied values
     *
     * @param int $max
     * @return $this
     */
    public function byMaxPrice(int $max): FilterProductsService
    {
        /**
         * This needs to be accounted for promotions
         * When promotions are build
         */
        $this->query->where('promo_price', '<=', $max);
        return $this;
    }
    /**
     * Get only those Products whose price fall between supplied values
     *
     * @param TrendingProduct $trendingProduct
     * @return $this
     */
    public function byTrendingProductSection(TrendingProduct $trendingProduct): FilterProductsService
    {
        /** This logic is incorrectly placed here. It should go into trending product section.
         * Currently I dont have a way to move it there.
         */
        $this->homePageSection = $trendingProduct;
        if ($trendingProduct->category_id) {
            $this->query->whereHas('categories', function (Builder $query) use ($trendingProduct) {
                $query->where('categories.id', $trendingProduct->category_id);
            });
        }
        if ($trendingProduct->tag_id) {
            $this->query->whereHas('tags', function (Builder $query) use ($trendingProduct) {
                $query->where('tags.id', $trendingProduct->tag_id);
            });
        }

        return $this;
    }

    /**
     * Get only those Products whose price fall between supplied values
     *
     * @param int $min
     * @param int $max
     * @return $this
     */
    public function byTabbedProductSection(TabbedSection $tabbedSection): FilterProductsService
    {
        /** This logic is incorrectly placed here. It should go into trending product section.
         * Currently I dont have a way to move it there.
         */
        $this->homePageSection = $tabbedSection;
        if ($tabbedSection->category_id) {
            $this->query->whereHas('categories', function (Builder $query) use ($tabbedSection) {
                $query->where('categories.id', $tabbedSection->category_id);
            });
            return $this;
        }
        $this->query->whereHas('tabbed_sections', function (Builder $query) use ($tabbedSection) {
            $query->where('tabbed_sections.id', $tabbedSection->id);
        });
        return $this;
    }


    /**
     * @return $this
     */
    public function newArrival()
    {
        $this->query->whereHas('tags', function (Builder $query) {
            $query->where('tag_id', 7);
        });
        return $this;
    }
    
    /**
     * @return $this
     */
    public function underOnePrice($amount)
    {
        $this->query->where('promo_price','<', $amount);
        return $this;
    }
    /**
     * set query sort order
     *
     * @param array $orders
     * @return FilterProductsService
     */
    public function sortBy(array $orders): FilterProductsService
    {
        foreach ($orders as $orderBy => $order) {
            $this->query->orderBy($orderBy, $order);
        }

        return $this;
    }

    /**
     * get latest products
     *
     * @return FilterProductsService
     */
    public function latest(): FilterProductsService
    {
        $this->query->latest();
        return $this;
    }

    /**
     * set wishlist relation
     *
     * @return FilterProductsService
     */
    public function wishlist(): FilterProductsService
    {
        $this->query->with('customerWishlist');
        return $this;
    }

    /**
     * set query relations
     *
     * @param array $relations
     * @return FilterProductsService
     */
    public function relations(array $relations): FilterProductsService
    {
        $this->query->with($relations);
        return $this;
    }

    /**
     * add selected column
     *
     * @param array $columns
     * @return FilterProductsService
     */
    public function select(array $columns): FilterProductsService
    {
        $this->query->select($columns);
        return $this;
    }

    /**
     * get filtered products collection
     *
     * @return Collection
     */
    public function get(): Collection
    {
        if ($this->excludeInactiveStore) {
            $this->excludeInactiveStores();
        }

        if ($this->includeOnlyParentProduct) {
            $this->includeOnlyParentProducts();
        }

        $products = $this->query->get();
        return $this->setCustomerWishlistAttribute($products);
    }

    /**
     * get filtered products collection with pagination
     *
     * @param null $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param null $page
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {

        $this->query->with('product_translates');

        if ($this->excludeInactiveStore) {
            $this->excludeInactiveStores();
        }

        if ($this->includeOnlyParentProduct) {
            $this->includeOnlyParentProducts();
        }

        //$this->logger->debug($this->getQueries());
        $products = $this->query->paginate($perPage, $columns, $pageName, $page);
        return $this->setCustomerWishlistAttribute($products);
    }

    public function getQueries()
    {
        $addSlashes = str_replace('?', "'?'", $this->query->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $this->query->getBindings());
    }

    /**
     * @return Collection
     */
    public function getBrands()
    {
        switch ($this->listMode) {
            case self::LIST_MODE_CATEGORY:
                // refactor this to a method
                return Brand::where('status', 1)->where('store_id',null)->where('is_approved',true)
                    ->whereHas('products', function (Builder $query) {
                        return $query->whereHas('categories', function ($category) {
                            return $category->where('status', 1)
                                // this is bad ..  have to supply table alias here. which framework should figure out itself
                                ->whereIn('categories.id', (array)$this->categories);
                        });
                    })
                    ->orderBy('sort_order', 'asc')
                    ->get();
                break;
            case self::LIST_MODE_TAB_SECTION:
                $tabbedSection = $this->homePageSection;
                return $this->tabbedProductsBrands($tabbedSection);
                break;
            case self::LIST_MODE_TRENDING_SECTION:
                $trendingProducts = $this->homePageSection;
                return $this->trendingProductsBrands($trendingProducts);
                break;
            case self::LIST_MODE_NONE;
                return Brand::orderBy('sort_order', 'asc')->get();
        }
    }


    /**
     * get filtered products count
     *
     * @return int
     */
    public function count(): int
    {
        if ($this->excludeInactiveStore) {
            $this->excludeInactiveStores();
        }

        if ($this->includeOnlyParentProduct) {
            $this->includeOnlyParentProducts();
        }

        return $this->query->count();
    }

    /**
     * These methods are put here from Product List  featutre.. We still need to implement these correctly
     */
    /**
     * get filtered products count
     *
     * @return int
     */
    public function getMinPrice(): int
    {
        return 0;
    }

    /**
     * get filtered products count
     *
     * @return int
     */
    public function getMaxPrice(): int
    {
        return 100;
    }

    /**
     * get all attributes & their options
     *
     * @return Collection
     */
    public function getAttributes($all_products): Collection
    {
        /*if($this->listMode == 0){
            return Attribute::with('options')->whereNull('parent_id')->get()->groupBy('title');
        }*/
//        $product_ids = $this->query->pluck('id')->toArray();
        $product_ids = $all_products->pluck('id')->toArray();
        $pro_attr = \App\Models\ProductAttribute::whereIn('product_id', $product_ids);
        $product_attribute_ids = $pro_attr->pluck('attribute_id')->toArray();
        $product_options_ids = $pro_attr->pluck('option_id')->toArray();
        $attr = Attribute::with(['options' => function ($q) use ($product_options_ids) {
            return $q->whereIn('id', $product_options_ids);
        }])->whereIn('id', $product_attribute_ids)->whereNull('parent_id')->get()->groupBy('title');

        return $attr;
    }

    /**
     * get number of products
     *
     * @param int $total
     * @return FilterProductsService
     */
    public function take(int $total): FilterProductsService
    {
        $this->query->take($total);
        return $this;
    }

    /**
     * get single product by id
     *
     * @param int $id
     * @return Product
     */
    public function find(int $id): Product
    {
        return $this->query->where('id', $id)->firstOrFail();
    }

    /**
     * get single product
     *
     * @return Product
     */
    public function first(): Product
    {
        return $this->query->first();
    }

    /**
     * get single product
     *
     * @return Product
     */
    public function firstOrFail(): Product
    {
        return $this->query->firstOrFail();
    }

    /**
     * get categories
     *
     * @param int/array $categories
     * @param bool $include_sub_cats_products
     * @return array
     */
    protected function getCategories($categories, bool $include_sub_cats_products = false): array
    {
        if (!is_array($categories)) {
            $categories = [$categories];
        }

        if ($include_sub_cats_products === false) {
            return $categories;
        }

        $get_sub_categories = Category::whereIn('parent_id', $categories)->active()->get()->pluck('id');
        if (empty($get_sub_categories)) {
            return $categories;
        }

        $collection = collect($categories);
        return $collection->merge($get_sub_categories)->unique()->values()->all();
    }

    /**
     * exclude inactive stores
     *
     * @return void
     */
    protected function excludeInactiveStores(): void
    {
        if ($this->excludeInactiveStoreAdded) {
            return;
        }

        $this->excludeInactiveStoreAdded = true;
        $this->query->whereHas('store', function (Builder $query) {
            return $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
        });
    }

    /**
     * include only parent products
     *
     * @return FilterProductsService
     */
    protected function includeOnlyParentProducts(): FilterProductsService
    {
        $this->query->whereNull('parent_id');
        return $this;
    }

    /**
     * set wishlist attribute
     *
     * @param Collection $products
     * @return Collection|LengthAwarePaginator
     */
    protected function setCustomerWishlistAttribute($products)
    {
        if (!isset($this->customer_id)) {
            return $products;
        }
        $wishlistService = new WishlistService();
        $customer_wishlists = $wishlistService->getCustomerWishlist($this->customer_id)->pluck('product_id');
        $products->map(function ($item) use ($customer_wishlists) {
            $item->wishlist = $customer_wishlists->contains($item->id);
            return $item;
        });

        return $products;
    }

    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    private function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~',"'","`"];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    /**
     * get tabbed products brands
     *
     * @param single row $tabbedSection
     * @return collection $brands
     */
    protected function tabbedProductsBrands($tabbedSection)
    {
        $brands = Brand::where('status', 1)
            ->whereHas('products', function (Builder $query) use ($tabbedSection) {

                if ($tabbedSection->category_id != null) {
                    return $query->whereHas('categories', function ($category) use ($tabbedSection) {
                        return $category->where('status', 1)->where('categories.id', $tabbedSection->category_id);
                    });
                    return $this;
                }

                return $this->query->whereHas('tabbed_sections', function (Builder $query) use ($tabbedSection) {
                    $query->where('tabbed_sections.id', $tabbedSection->id);
                });
            })
            ->orderBy('sort_order', 'asc')
            ->get();

        return $brands;
    }

    /**
     * get trending products brands
     *
     * @param single row $tabbedSection
     * @return collection $brands
     */
    protected function trendingProductsBrands($trendingProduct)
    {
        $brands = Brand::where('status', 1)
            ->whereHas('products', function (Builder $query) use ($trendingProduct) {

                if ($trendingProduct->category_id) {
                    $this->query->whereHas('categories', function (Builder $query) use ($trendingProduct) {
                        $query->where('categories.id', $trendingProduct->category_id);
                    });
                }

                if ($trendingProduct->tag_id) {
                    $this->query->whereHas('tags', function (Builder $query) use ($trendingProduct) {
                        $query->where('tags.id', $trendingProduct->tag_id);
                    });
                }
                return $this;
            })
            ->orderBy('sort_order', 'asc')
            ->get();

        return $brands;
    }

    /**
     * get products by name or sku
     * @param $keyWord
     * @return $this
     */
    public function byKeywordOrSkuLike($keyWord): FilterProductsService
    {
        $this->query->where('title', 'like', '%'.$keyWord.'%')->Orwhere('sku', 'like', '%'.$keyWord.'%');
        return $this;
    }
}
