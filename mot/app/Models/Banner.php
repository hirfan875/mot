<?php

namespace App\Models;

use App\Traits\MediaHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Banner
 *
 * @property int $id
 * @property bool|null $status
 * @property string|null $title
 * @property string|null $image
 * @property string|null $button_text
 * @property string|null $button_url
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $edit_url
 * @property-read \App\Models\HomepageSorting|null $sort
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereButtonUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Banner extends Model
{
    use HasFactory, MediaHelpers;

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
     * Get the sort order
     */
    public function sort()
    {
        return $this->morphOne(HomepageSorting::class, 'sortable');
    }

    /**
     * get edit_url attribute
     */
    public function getEditUrlAttribute()
    {
        return route('admin.banners.edit', ['banner' => $this->id]);
    }
    
    /**
     * get banner translate
     *
     * @return \Illuminate\Support\Collection
     */
    public function banner_translate()
    {
        return $this->hasMany(BannerTranslate::class, 'banner_id');
    }
    
    /**
     * get banner translates
     *
     * @return \Illuminate\Support\Collection
     */
    public function banner_translates()
    {
        return $this->hasOne(BannerTranslate::class, 'banner_id')->where('language_id', getLocaleId(app()->getLocale()));
    }
}
