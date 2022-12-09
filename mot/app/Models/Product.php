<?php

namespace App\Models;

use App\Helpers\UtilityHelpers;
use App\Traits\MediaHelpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductTranslate;
use Storage;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property bool|null $status
 * @property int|null $parent_id
 * @property int|null $brand_id
 * @property int $store_id
 * @property bool|null $is_approved
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $type
 * @property string|null $sku
 * @property string|null $price
 * @property string|null $promo_price
 * @property int|null $promo_source_id
 * @property string|null $promo_source_type
 * @property int|null $discount
 * @property string|null $discount_type
 * @property int|null $stock
 * @property bool|null $free_delivery
 * @property string|null $image
 * @property string|null $data
 * @property string|null $meta_title
 * @property string|null $meta_desc
 * @property string|null $meta_keyword
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $delivery_fee
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property bool|float|int $discounted_price
 * @property int|null $discount_source
 * @property int|null $discount_source_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductAttribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \App\Models\Brand|null $brand
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $bundle_products
 * @property-read int|null $bundle_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\Wishlist|null $customerWishlist
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductGallery[] $gallery
 * @property-read int|null $gallery_count
 * @property-read mixed $image_url
 * @property-read float $product_price
 * @property-read mixed $rating
 * @property-read Product|null $parent
 * @property-read \App\Models\ProductPrice|null $productPrice
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductReview[] $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $saveGallery
 * @property-read int|null $save_gallery_count
 * @property-read \App\Models\Store $store
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TabbedSection[] $tabbed_sections
 * @property-read int|null $tabbed_sections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductAttribute[] $variation_attributes
 * @property-read int|null $variation_attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $variations
 * @property-read int|null $variations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product active()
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFreeDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePromoPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePromoSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePromoSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductTranslate[] $product_translate
 * @property-read int|null $product_translate_count
 */
class Product extends Model
{
    use HasFactory, HasSlug, SoftDeletes, MediaHelpers;

    const FIXED = 'fixed';
    const PERCENTAGE = 'percentage';

    const TYPE_SIMPLE = 'simple';
    const TYPE_VARIABLE = 'variable';
    const TYPE_BUNDLE = 'bundle';
    const TYPE_VARIATION = 'variation';
    const TYPE_CHILD = 'child';

    const PRODUCT_THUMBNAIL = 'product_thumbnail';
    const PRODUCT_DETAIL = 'product_detail';
    const PRODUCT_LISTING = 'product_listing';
    const ADMIN = 'admin';
    const SELLER = 'seller';
    const TRANDYOL_AUTO = 'trendyol-auto';
    const TRANDYOL_MANUAL = 'trendyol-manual';

    protected $guarded = [];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
        'is_approved' => true,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool',
        'is_approved' => 'bool',
        'free_delivery' => 'bool'
    ];

    /**
     * should be static
     * Get the options for generating the slug.
     *
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnCreate()
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Scope a query to only include active products
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereStatus(true)->whereIsApproved(true);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNewArrivals($query)
    {
        $tenDaysBefore = config('app.olddate');
        $currentDate = Carbon::now()->toDateString();
        return $query->where('created_at', '>=', $tenDaysBefore)->where('created_at', '<=', $currentDate);
    }

    /**
     * save product gallery
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function saveGallery()
    {
        return $this->belongsToMany(Product::class, ProductGallery::class, 'product_id', 'image')->withTimestamps();
    }

    /**
     * get product variations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    /**
     * get daily deal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function daily_deal()
    {
        return $this->hasOne(DailyDeal::class);
    }


    /**
     * get daily deal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function flash_deal()
    {
        return $this->hasOne(FlashDeal::class);
    }

    /**
     * if this is a variation , get its parent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id')->withTrashed();
    }

    /**
     * get product categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /**
     * product tags
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    /**
     * product translate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product_translate()
    {
        return $this->hasMany(ProductTranslate::class, 'product_id');
    }

    /**
     * product translates
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function product_translates()
    {
        return $this->hasOne(ProductTranslate::class, 'product_id')->where('language_id', getLocaleId(app()->getLocale()));
    }

    /**
     * bundle products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bundle_products()
    {
        return $this->belongsToMany(Product::class, 'bundle_product', 'bundle_id');
    }

    /**
     * product tags
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tabbed_sections()
    {
        return $this->belongsToMany(TabbedSection::class, 'tabbed_products');
    }

    /**
     * get product gallery
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gallery() {
        return $this->hasMany(ProductGallery::class, 'product_id')->orderBy('sort_order', 'asc');
    }

    /**
     * get product attributes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    /**
     * get variation attributes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variation_attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'variation_id');
    }

    /**
     * Get the store that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the store that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function reviews()
    {
        return $this->hasManyThrough(ProductReview::class, OrderItem::class);
    }

    /**
     * TODO  Think a better way to cache this value, perhaps within a DB field
     */
    public function getRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->average('rating');
    }

    public function getRatingCountAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    /**
     * get single brand associated with the product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    /**
     * check product in customer wishlist
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customerWishlist()
    {
        // TODO @tahir find a way to get rid of auth from model .. This is a session based feature and should not be in model
        $customer_id = 0;
        if (auth('customer')->check()) {
            $customer_id = auth('customer')->user()->id;
        }

        return $this->hasOne(Wishlist::class, 'product_id')->where('customer_id', $customer_id);
    }

    /**
     * product price relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productPrice()
    {
        return $this->hasOne(ProductPrice::class);
    }

    /**
     * get product_price attribute
     *
     * @return float
     */
    public function getProductPriceAttribute()
    {
        if ($this->productPrice) {
            return $this->productPrice->price;
        }

        return $this->price;
    }

    /**
     * @return bool|float|int
     */
    public function getDiscountedPriceAttribute()
    {
        if (!$this->discount) {
            return $this->price;
        }
        if ($this->discount_type === Product::PERCENTAGE) {
            return $this->price * (1 - $this->discount / 100);
        }
        if ($this->discount > 0 && $this->discount < $this->price) {
            return $this->price - $this->discount;
        }
        return $this->price;
    }

    /**
     * check product created date with previous 10 days
     *
     * @return boolean
     */
    public function isNew()
    {
        $old_date = config('app.olddate');
        $created_at = date('Y-m-d', strtotime($this->created_at));

         $tags = $this->tags;
        /** @var Tag $tag */
        foreach ($tags as $tag) {
            if ($tag->id === Tag::NEW_ID) {
                return true;
            }
        }

        if ($created_at <= $old_date) {
            return false;
        }



        return true;
    }

    /**
     * check product is in sale
     *
     * @return boolean
     */
    public function isSale()
    {
        return $this->promo_price < $this->price;
    }

    /**
     * check product is in top products
     *
     * @return boolean
     */
    public function isTop()
    {
        $tags = $this->tags;
        /** @var Tag $tag */
        foreach ($tags as $tag) {
            if ($tag->id === Tag::TOP_ID) {
                return true;
            }
        }
        return false;
    }

    public function toCartProduct(Cart $cart)
    {
        $cartProduct =  CartProduct::create([
            'cart_id' => $cart->id,
            'product_id' => $this->id,
            'unit_price' => $this->price,
            'delivery_fee' => $this->delivery_fee,
            'currency_id' => $this->currency_id,
        ]);
        $cart->cart_products->add($cartProduct);
        return $cartProduct;
    }

    public function isVariable() {
        return $this->type === self::TYPE_VARIABLE;
    }

    public function isVariation() {
        return $this->type === self::TYPE_VARIATION;
    }

    public function isSimple() {
        return $this->type === self::TYPE_SIMPLE;
    }

    public function isBundle() {
        return $this->type === self::TYPE_BUNDLE;
    }

    public function hasNoParent() {
        return is_null($this->parent_id);
    }

    public function IsWishlist()
    {
        $result = false;
        $customer_id = 0;
        if (Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
        } else if (Auth('sanctum')->check()) {
            $customer_id = Auth('sanctum')->user()->id;
        }

        $wishlist = $this->hasOne(Wishlist::class, 'product_id')->where('customer_id', $customer_id);
        if ($wishlist->count() > 0) {
            $result = true;
        }
        return $result;
    }

    public function getImageUrlAttribute()
    {
        if ($this->gallery->count() > 0 && isset($this->gallery[0]->image)) {
            return UtilityHelpers::getCdnUrl(route('resize', [163, 184, $this->gallery[0]->image]));
        }
        return UtilityHelpers::getCdnUrl(route('resize', [163, 184, 'placeholder.jpg']));
    }

    /**
     * @param $height
     * @param $width
     * @return string
     */
    public function resize_image_url($height, $width)
    {
        if ($this->gallery->count() > 0) {
            return UtilityHelpers::getCdnUrl(route('resize', [$height, $width, $this->gallery[0]->image]));
        }

        return UtilityHelpers::getCdnUrl(route('resize', [163, 184, 'placeholder.jpg']));
    }

   /**
     * @param $height
     * @param $width
     * @return string
     */
    public function media_image($type=null)
    {
        if ($this->gallery->count() > 0) {
            return UtilityHelpers::getCdnUrl($this->gallery[0]->getMedia('image', $type));
            // return $this->gallery[0]->getMedia('image', $type);
        }

        return UtilityHelpers::getCdnUrl(route('resize', [163, 184, 'placeholder.jpg']));
    }

    public function product_listing()
    {
        if ($this->image) {
            $path = 'product_listing/' . $this->image;
            $check_file = Storage::exists($path);
            if ($check_file){
                return UtilityHelpers::getCdnUrl('storage/product_listing/' . $this->image);
            }
        }

        if ($this->gallery->count() > 0 && $this->gallery[0]) {

            return UtilityHelpers::getCdnUrl($this->gallery[0]->getMedia('image', self::PRODUCT_LISTING ));
        }
        return UtilityHelpers::getCdnUrl(route('resize', [163, 184, 'placeholder.jpg']));
    }

    public function product_detail($key = 0)
    {
        if ($this->gallery->count() > 0) {
            return UtilityHelpers::getCdnUrl($this->gallery[$key]->getMedia('image', self::PRODUCT_DETAIL ));
        }
        if ($this->image) {
            $path = 'product_listing/' . $this->image;
            $check_file = Storage::exists($path);
            if ($check_file){
                return UtilityHelpers::getCdnUrl('storage/product_listing/' . $this->image);
            }
        }
        return UtilityHelpers::getCdnUrl(route('resize', [515, 320, 'placeholder.jpg']));
    }

    public function product_original($key = 0)
    {
        if ($this->gallery->count() > 0) {
            return UtilityHelpers::getCdnUrl($this->gallery[$key]->getMedia('image'));
        }
        return UtilityHelpers::getCdnUrl(route('resize', [515, 320, 'placeholder.jpg']));
    }

    public function product_thumbnail($key = 0)
    {
        if ($this->gallery->count() > 0) {

        return UtilityHelpers::getCdnUrl($this->gallery[$key]->getMedia('image', self::PRODUCT_THUMBNAIL ));

        }
        return UtilityHelpers::getCdnUrl(route('resize', [163, 184, 'placeholder.jpg']));
    }

    /**
     * @return bool
     */
    public function soldOut() : bool
    {
        if ($this->isSimple() || $this->isBundle() ){
            return $this->stock <= 0;
        }
        foreach ($this->variations as $variant) {
            if ($variant->stock > 0) {
                return false;
            }
        }
        return true;
    }


    /**
     * @return string
     */
    public function getViewRoute()
    {
        return route('product', [$this->slug, $this->id]);
    }

    public function getCreatedUserAttribute()
    {
        if($this->attributes['created_by'] == Product::SELLER){
            return StoreStaff::where('id', $this->attributes['created_by_id'])->first();
        }
        return User::where('id', $this->attributes['created_by_id'])->first();
    }

    public function getTotalStock()
    {
        if ($this->isSimple() || $this->isBundle()) {
            return $this->attributes['stock'];
        }

        $stock = 0;
        foreach ($this->variations as $variant) {
            if ($variant->stock > 0) {
                $stock += $variant->stock;
            }
        }
        return $stock;
    }

    /**
     * Get the approved reviews
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function approved_reviews()
    {
        return $this->hasManyThrough(ProductReview::class, OrderItem::class)->where('is_approved', true);
    }
    
    public function trendyolCategories()
    {
        return $this->belongsTo(TrendyolCategories::class);
    }
}
