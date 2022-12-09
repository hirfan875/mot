<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\TagTranslate;

/**
 * App\Models\Tag
 *
 * @property int $id
 * @property bool|null $status
 * @property int|null $is_admin
 * @property string|null $title
 * @property string|null $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tag active()
 * @method static \Database\Factories\TagFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tag extends Model
{
    use HasFactory, HasSlug;

    public const TOP_ID = 1;
    public const TRENDING_ID = 2;
    public const FEATURED_ID = 3;
    public const NEW_ID = 7;
    public const TOP = 'Top';
    public const TRENDING = 'Trending';
    public const FEATURED = 'Featured';
    public const NEW = 'New';

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
     * tag products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag');
    }

    /**
     * Scope a query to only include active tags
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereStatus(true);
    }
    
    /**
     * get tag translate
     *
     * @return \Illuminate\Support\Collection
     */
    public function tag_translate()
    {
        return $this->hasMany(TagTranslate::class, 'tag_id');
    }
    
    /**
     * get tag translates
     *
     * @return \Illuminate\Support\Collection
     */
    public function tag_translates()
    {
        return $this->hasOne(TagTranslate::class, 'tag_id')->where('language_id', getLocaleId(app()->getLocale()));
    }
}
