<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaHelpers;

/**
 * App\Models\Slider
 *
 * @property int $id
 * @property bool $status
 * @property string|null $image
 * @property int|null $sort_order
 * @property string|null $button_text
 * @property string|null $button_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SliderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereButtonUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Slider extends Model
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
     * get slider translate
     *
     * @return \Illuminate\Support\Collection
     */
    public function slider_translate()
    {
        return $this->hasMany(SliderTranslate::class, 'slider_id');
    }
    
    /**
     * get slider translates
     *
     * @return \Illuminate\Support\Collection
     */
    public function slider_translates()
    {
        return $this->hasOne(SliderTranslate::class, 'slider_id')->where('language_id', getLocaleId(app()->getLocale()));
    }
    
}
