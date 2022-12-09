<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProductAttribute
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $variation_id
 * @property int|null $attribute_id
 * @property int|null $option_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Attribute|null $option
 * @property-read \App\Models\Attribute|null $attribute_by_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereVariationId($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\ProductAttributeFactory factory(...$parameters)
 */
class ProductAttribute extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * get attribute option data associated with the product
     *
     * @return Attribute
     */
    public function option()
    {
        return $this->hasOne(Attribute::class, 'id', 'option_id');
    }
    public function attribute_by_id()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }
}
