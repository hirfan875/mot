<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductPrice
 *
 * @property int $id
 * @property int|null $product_id
 * @property string|null $price
 * @property string|null $discount_source
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereDiscountSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductPrice extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * get product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
