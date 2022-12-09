<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\PageTranslate;

/**
 * App\Models\Page
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $data
 * @property string|null $meta_title
 * @property string|null $meta_desc
 * @property string|null $meta_keyword
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $page_url
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Page extends Model
{
    use HasFactory, HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }

    /**
     * get page url
     *
     * @return url
     */
    public function getPageUrlAttribute()
    {
        $slug = $this->slug;

        if ( $slug != 'home' ) {
            return route('page', ['slug' => $slug]);
        }

        return route('home');
    }
    
    /**
     * get page translate
     *
     * @return \Illuminate\Support\Collection
     */
    public function page_translate()
    {
        return $this->hasMany(PageTranslate::class, 'page_id');
    }
    
    /**
     * get page translates
     *
     * @return \Illuminate\Support\Collection
     */
    public function page_translates()
    {
        return $this->hasOne(PageTranslate::class, 'page_id')->where('language_id', getLocaleId(app()->getLocale()));
    }
}
