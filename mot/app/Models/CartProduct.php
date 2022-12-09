<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\UtilityHelpers;

/**
 * App\Models\CartProduct
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property int $unit_price
 * @property int $delivery_fee
 * @property int $currency_id
 * @property string $price_updated_on
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct wherePriceUpdatedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $message
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereMessage($value)
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Cart $cart
 * @property-read string $image
 * @property-read string $title
 * @method static \Database\Factories\CartProductFactory factory(...$parameters)
 * @property-read mixed $sub_total
 */
class CartProduct extends Model
{
    use HasFactory;

    protected $table = 'cart_product';

    protected $fillable = ['cart_id', 'product_id', 'quantity', 'unit_price', 'delivery_fee', 'currency_id', 'message', 'price_updated_on'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function toOrderItem(StoreOrder  $storeOrder): OrderItem
    {

        if($this->product->stock < $this->quantity){
            throw new \Exception(__('Not enough stock for ') . $this->product->title);
        }

        $orderItem = $storeOrder->order_items()->updateOrCreate(
            [
                'product_id' => $this->product_id, 'store_order_id' => $storeOrder->id
            ], [
                    'store_order_id' => $storeOrder->id,
                    'product_id' => $this->product_id,
                    'quantity' => $this->quantity,
                    'unit_price' => $this->unit_price,
                    'delivery_fee' => $this->delivery_fee ?? 0, // TODO need a better fix
                    'currency_id' => $this->currency_id,
                    'discount' => $this->discount
            ]);

        /**
         * TODO this could be source of a problem ...
         * We just move stock from available to part of an order.
         */

        return $orderItem;
    }

    public function getSubTotalAttribute()
    {
        return $this->unit_price * $this->quantity;
    }


    /**
     * @return string
     */
    public function getImageAttribute(): string
    {
        if ($this->product->parent && $this->product->parent->gallery->count() > 0) {
            return '/storage/original/' . $this->product->parent->gallery[0]->image;
        }
        if ($this->product->gallery->count() > 0) {
            return '/storage/original/' . $this->product->gallery[0]->image;
        }
        return asset('assets/frontend') . '/assets/img/placeholder-cart-prod.jpg';
    }

    /**
     * @return string
     */
    public function getTitleAttribute(): string
    {
        if ($this->product->isSimple()) {
            return $this->product->title;
        }

        $attributeNames = UtilityHelpers::getVariationNames($this->product);
        return isset($this->product->parent->title) ? $this->product->parent->title : $this->product->title. ' '. implode(', ', $attributeNames);
    }

}
