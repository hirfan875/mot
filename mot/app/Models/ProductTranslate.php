<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

/**
 * App\Models\ProductTranslate
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $language_id
 * @property string|null $language_code
 * @property string|null $title
 * @property string|null $data
 * @property string|null $meta_title
 * @property string|null $meta_desc
 * @property string|null $meta_keyword
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereMetaDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereMetaKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductTranslate extends Model
{
    use HasFactory;
    
    protected $fillable = ['product_id','language_id','language_code','title','data','meta_title','meta_desc','meta_keyword'];
    
     /**
     * Translate products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
