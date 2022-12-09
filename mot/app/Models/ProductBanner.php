<?php

namespace App\Models;

use App\Traits\MediaHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductBanner
 *
 * @property int $id
 * @property bool|null $status
 * @property bool|null $is_default
 * @property string|null $banner_1
 * @property string|null $banner_1_url
 * @property string|null $banner_2
 * @property string|null $banner_2_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner whereBanner1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner whereBanner1Url($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner whereBanner2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner whereBanner2Url($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBanner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductBanner extends Model
{
    use HasFactory, MediaHelpers;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
        'is_default' => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool',
        'is_default' => 'bool',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
