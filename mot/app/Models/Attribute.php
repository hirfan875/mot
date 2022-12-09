<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\AttributeTranslate;

/**
 * App\Models\Attribute
 *
 * @property int $id
 * @property bool|null $status
 * @property int|null $parent_id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $type
 * @property string|null $code
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Attribute[] $options
 * @property-read int|null $options_count
 * @property-read Attribute|null $parent
 * @method static \Database\Factories\AttributeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Attribute extends Model
{
    use HasFactory, HasSlug;

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
     * get attribute options
     *
     * @return \Illuminate\Support\Collection
     */
    public function options()
    {
        return $this->hasMany(Attribute::class, 'parent_id');
    }

    /**
     * get attribute parent
     *
     * @return \Illuminate\Support\Collection
    */

    public function parent(){
        return $this->belongsTo(Attribute::class, 'parent_id');
    }
    
    /**
     * get attribute translate
     *
     * @return \Illuminate\Support\Collection
     */
    public function attribute_translate()
    {
        return $this->hasMany(AttributeTranslate::class, 'attribute_id');
    }
    
    /**
     * get attribute translates
     *
     * @return \Illuminate\Support\Collection
     */
    public function attribute_translates()
    {
        return $this->hasOne(AttributeTranslate::class, 'attribute_id')->where('language_code', app()->getLocale());
    }
}
