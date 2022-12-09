<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Traits\MediaHelpers;
use App\Models\CategoryTranslate;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property bool|null $status
 * @property int|null $parent_id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $image
 * @property string|null $data
 * @property string|null $meta_title
 * @property string|null $meta_desc
 * @property string|null $meta_keyword
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $category
 * @property-read int|null $category_count
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $subcategories
 * @property-read int|null $subcategories_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category active()
 * @method static \Database\Factories\CategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMetaDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMetaKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $commission
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCommission($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductBanner[] $product_banners
 * @property-read int|null $product_banners_count
 */
class Category extends Model
{
    use HasFactory, HasSlug, MediaHelpers;
    
    protected $fillable = [ 'id', 'title', 'parent_id', 'status', 'slug', 'commission', 'google_category', 'image ', 'banner', 'data', 'meta_title', 'meta_desc', 'meta_keyword', 'sort_order' ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool',
    ];

    /**
     * Get the options for generating the slug.
     *
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Scope a query to only include active categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereStatus(true);
    }

    /**
     * get category sub categories
     *
     * @return \Illuminate\Support\Collection
     */
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order', 'asc')->with('subcategories')->where('status', 1);
    }

    public function headerSubcategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order', 'asc')->with('subcategories')->where('status', 1);
    }
    
    
//    public function headerSubcategories()
//    {
//        return $this->hasMany(Category::class, 'parent_id')->whereHas('products', 
//                function($query){
//            $query->active();
//        })->orderBy('sort_order', 'asc')->with('subcategories')->where('status', 1);
//    }

    /**
     * get category translate
     *
     * @return \Illuminate\Support\Collection
     */
    public function category_translate()
    {
        return $this->hasMany(CategoryTranslate::class, 'category_id');
    }

    /**
     * get category translates
     *
     * @return \Illuminate\Support\Collection
     */
    public function category_translates()
    {
        return $this->hasOne(CategoryTranslate::class, 'category_id')->where('language_id', getLocaleId(app()->getLocale()));
    }

    /**
     * get parent category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * this is for factory
     *
     * @return \Illuminate\Support\Collection
     */
    public function category()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * category products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * category banners
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function product_banners()
    {
        return $this->belongsToMany(ProductBanner::class);
    }
    
    public function trendyolCategories()
    {
        return $this->belongsTo(TrendyolCategories::class);
    }
    
}
