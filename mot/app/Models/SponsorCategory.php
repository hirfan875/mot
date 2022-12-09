<?php

namespace App\Models;

use App\Helpers\UtilityHelpers;
use App\Traits\MediaHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SponsorCategory
 *
 * @property int $id
 * @property int|null $sponsor_section_id
 * @property string|null $title
 * @property string|null $image
 * @property string|null $button_text
 * @property string|null $button_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory whereButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory whereButtonUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory whereSponsorSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SponsorCategory extends Model
{
    use HasFactory, MediaHelpers;

    const SPONSOR_CATEGORY = 'sponsor_category';

    protected $guarded = [];

    public function media_image($type=null)
    {
        if ($this->image != null) {
            return UtilityHelpers::getCdnUrl($this->getMedia('image', $type));
        }
        return UtilityHelpers::getCdnUrl(route('resize', [163, 184, 'placeholder.jpg']));
    }
    
    /**
     * get banner translate
     *
     * @return \Illuminate\Support\Collection
     */
    public function sponsor_category_translate()
    {
        return $this->hasMany(SponsorCategoriesTranslate::class, 'sponsor_category_id');
    }
    
    /**
     * get banner translates
     *
     * @return \Illuminate\Support\Collection
     */
    public function sponsor_category_translates()
    {
        return $this->hasOne(SponsorCategoriesTranslate::class, 'sponsor_category_id')->where('language_id', getLocaleId(app()->getLocale()));
    }
}
