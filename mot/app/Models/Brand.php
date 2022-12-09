<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Traits\MediaHelpers;
use App\Models\BrandTranslate;

/**
 * App\Models\Brand
 *
 * @property int $id
 * @property bool|null $status
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\BrandFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereMetaDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereMetaKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Brand extends Model
{
    use HasFactory, HasSlug, MediaHelpers;

    protected $fillable = ['title','store_id','is_approved','data','meta_title','meta_desc','meta_keyword'];

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
     * get brand products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    /**
     * @return string
     */
    public function getViewRoute()
    {
        return route('brand', [$this->slug]);
    }

    /**
     * get brand translate
     *
     * @return \Illuminate\Support\Collection
     */
    public function brand_translate()
    {
        return $this->hasMany(BrandTranslate::class, 'brand_id');
    }

    /**
     * get brand translates
     *
     * @return \Illuminate\Support\Collection
     */
    public function brand_translates()
    {
        return $this->hasOne(BrandTranslate::class, 'brand_id')->where('language_id', getLocaleId(app()->getLocale()));
    }
}
