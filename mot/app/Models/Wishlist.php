<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wishlist
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\WishlistFactory factory(...$parameters)
 */
class Wishlist extends Model
{
    use HasFactory;

    /**
     * get product
     *
     * @return Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * get customer
     *
     * @return Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
