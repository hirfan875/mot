<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cart
 *
 * @property mixed $cart_data
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $address_id
 * @property string $session_id
 * @property int|null $coupon_id
 * @property int|null $total
 * @property int|null $sub_total
 * @property int|null $currency_id
 * @property string|null $total_updated_on
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CartProduct[] $cart_products
 * @property-read int|null $cart_products_count
 * @property-read \App\Models\Coupon|null $coupon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTotalUpdatedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereAddressId($value)
 * @property-read int $delivery_fee
 * @method static \Database\Factories\CartFactory factory(...$parameters)
 * @property int|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereStatus($value)
 */
class Cart  extends Model
{
    use HasFactory;

    protected $table = 'carts';

    const OPEN_ID           = 1;
    const BEING_ORDER_ID    = 2;
    const TERMINATED_ID     = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'cart_data',
        'session_id',
        'currency_id',
        'status',

    ];

    public function setCartDataAttribute($value)
    {
        $this->attributes['cart_data'] = serialize($value);
    }

    public function getCartDataAttribute($value)
    {
        return unserialize($value);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, CartProduct::class);
    }

    public function cart_products()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get uniques Stores currently in this order
     */
    public function getStores()
    {
        $stores = [];
        /** @var CartProduct $cartProduct */
        foreach ($this->cart_products as $cartProduct) {
            $stores[$cartProduct->product->store->id][] = $cartProduct;
        }
        return collect($stores);
    }

    /**
     * @return int
     */
//    public function getDeliveryFeeAttribute() :int
//    {
//        $total = 0;
////        dd($this->cart);
//        foreach ($this->cart_products as $product) {
//            $total += abs($product->delivery_fee * $product->quantity);
//        }
//        
//        return $total;
//    }

    /**
     *
     */
    public function recalculate(){

        $total = 0;
        foreach ($this->cart_products as $product) {
            $total += abs($product->unit_price * $product->quantity);
        }
        $this->sub_total = $total;
    }
}
