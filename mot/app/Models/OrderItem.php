<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Monolog\Logger;
use App\Jobs\RefundStoreOrder;
use Illuminate\Support\Collection;
use App\Jobs\CancelOrder;
use Carbon\Carbon;

/**
 * App\Models\OrderItem
 *
 * @property int $id
 * @property int $store_order_id
 * @property int $product_id
 * @property int $quantity
 * @property int $unit_price
 * @property int $delivery_fee
 * @property int $currency_id
 * @property int $exchange_rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereStoreOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereExchangeRate($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\OrderItemFactory factory(...$parameters)
 * @property-read mixed $total
 * @property-read \App\Models\ProductReview|null $product_review
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReturnOrderItems[] $return_order_items
 * @property-read int|null $return_order_items_count
 * @property-read \App\Models\StoreOrder $store_order
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItemTransaction[] $order_item_transactions
 * @property-read int|null $order_item_transactions_count
 */
class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const UNIITIATED_ID = 0;
    public const CONFIRMED_ID = 1;
    public const PAID_ID = 2;
    public const READY_ID = 3;
    public const SHIPPED_ID = 4;
    public const DELIVERED_ID = 5;
    public const CANCEL_REQUESTED_ID = 6;
    public const CANCELLED_ID = 7;
    public const RETURN_REQUESTED_ID = 8;
    public const DELIVERY_FAILURE_ID = 9;
    public const RETURN_ACCEPTED_ID = 10;
    public const TERMINATED_ID = 11;

    public const ARCHIVED = 1;
    public const NOTARCHIVED = 0;
    
     private static $statusTransition = [
        self::TERMINATED_ID => [],
        self::UNIITIATED_ID => [self::CONFIRMED_ID, self::TERMINATED_ID, self::CANCELLED_ID, self::CANCEL_REQUESTED_ID],
        self::CONFIRMED_ID => [self::PAID_ID, self::TERMINATED_ID],
        self::PAID_ID => [self::READY_ID, self::CANCELLED_ID],
        self::READY_ID => [self::SHIPPED_ID, self::CANCEL_REQUESTED_ID],
        self::SHIPPED_ID => [self::DELIVERED_ID, self::DELIVERY_FAILURE_ID],
        self::DELIVERED_ID => [self::RETURN_REQUESTED_ID],
        self::CANCEL_REQUESTED_ID => [ self::SHIPPED_ID, self::CANCELLED_ID],
        self::CANCELLED_ID => [],
        self::RETURN_REQUESTED_ID => [self::RETURN_ACCEPTED_ID],
        self::DELIVERY_FAILURE_ID => [],
    ];
     
     private static $statusString = [
        self::TERMINATED_ID => 'Terminated',
        self::UNIITIATED_ID => 'Uninitiated',
        self::CONFIRMED_ID => 'Confirmed',
        self::PAID_ID => 'Paid',
        self::READY_ID => 'Ready To Ship',
        self::SHIPPED_ID => 'Shipped',
        self::DELIVERED_ID => 'Delivered',
        self::CANCEL_REQUESTED_ID => 'Cancellation Requested',
        self::CANCELLED_ID => 'Cancelled',
        self::RETURN_REQUESTED_ID => 'Return Requested',
        self::DELIVERY_FAILURE_ID => 'Delivery Failure',
    ];
     
     public static $orderTrackStatus = [
        self::CONFIRMED_ID => ['Confirmed'],
        self::PAID_ID => ['Confirmed'],
        self::READY_ID => ['Confirmed', 'Processing'],
        self::SHIPPED_ID => ['Confirmed', 'Processing', 'On-the-way'],
        self::DELIVERED_ID => ['Confirmed', 'Processing', 'On-the-way', 'Delivered'],
        self::CANCEL_REQUESTED_ID => ['Track', 'view-cancel'],
        self::CANCELLED_ID => ['view-cancel'],
        self::RETURN_REQUESTED_ID => ['Track', 'Return', 'Feedback'],
        self::DELIVERY_FAILURE_ID => ['Track', 'Return', 'Feedback'],
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product_review()
    {
        return $this->hasOne(ProductReview::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function return_order_items()
    {
        return $this->hasMany(ReturnOrderItems::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_item_transactions()
    {
        return $this->hasMany(OrderItemTransaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->hasOneThrough(Order::class, StoreOrder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store_order()
    {
        return $this->belongsTo(StoreOrder::class);
    }

    public function getTotalAttribute()
    {
        return ($this->unit_price + $this->delivery_fee) * $this->quantity;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'store_order_id' => $this->store_order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'product' => $this->product->toArray(),
        ];
    }

    /**
     * @return bool
     */
    public function hasProductReview() : bool
    {
        return $this->product_review()->count() > 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function isAbleToReview()
    {
        $customer_id = 0;
        if (Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
        } else if (Auth('sanctum')->check()) {
            $customer_id = Auth('sanctum')->user()->id;
        }

        if ($customer_id == 0) {
            return false;
        }

        $reviews = $this->reviews()->where('customer_id', $customer_id);
        if ($reviews->count() > 0) {
            return false;
        }
        return true;
    }
    
      

    public function toCancelRequest($ip)
    {
        
        $logger = getLogger('cancel-order');
        if (in_array(self::CANCELLED_ID, self::$statusTransition[$this->status])) {
            $this->toCancel($ip);
            return;
        }
        
        if (!in_array(self::CANCEL_REQUESTED_ID, self::$statusTransition[$this->status])) {
            throw new \Exception("Invalid Status Transition to CancelRequested" . $this->status);
        }

        $this->status = self::CANCEL_REQUESTED_ID;
        $this->save();
        
        if ($this->order->allStoreOrderItemsRequestedForCancellation()) {
            $this->store_order->status = StoreOrder::CANCEL_REQUESTED_ID;
            $this->store_order->save();
        }
        
        if ($this->order->allStoreOrderRequestedForCancellation()) {
            $this->order->status = Order::CANCEL_REQUESTED_ID;
            $this->order->save();
        }
        
        return $this;
    }
    
     /**
     * @return $this
     */
    public function toCancel($ip)
    {
        $logger = getLogger('cancel-order', Logger::DEBUG , 'logs/cancel-order.log');
        if (! in_array(self::CANCELLED_ID, self::$statusTransition[$this->status])) {
            return $this;
        }
        
        $this->status = self::CANCELLED_ID;
        $this->save();
        
        if ($this->store_order->allStoreOrderItemsCancelled()) {
            $this->store_order->status = StoreOrder::CANCELLED_ID;
            $this->store_order->save();
        }
        
        if ($this->store_order->order->allStoreOrderCancelled()) {
            $this->store_order->order->status = Order::CANCELLED_ID;
            $this->store_order->order->save();
        }
//        dd($this->store_order->order->store_orders()->count());
        
        $storeOrderCount  = $this->store_order->order->store_orders()->count() ;

        if (1 == $storeOrderCount) {
//            CancelOrder::dispatch($this->store_order->order,$ip); // we can cancel the order if , there was only one store order.
            // other wise we will have to issue refund
            return $this;
        }
        return $this;
//        RefundStoreOrder::dispatch($this->store_order,$ip); // Since there are multiple store orders under this order , we can only refund
    }
}
