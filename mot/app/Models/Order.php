<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $order_number
 * @property int $status
 * @property int $customer_id
 * @property int $address_id
 * @property int $sub_total
 * @property int $delivery_fee
 * @property int $tax
 * @property int|null $coupon_id
 * @property int $currency_id
 * @property string $address
 * @property \Illuminate\Support\Carbon $order_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Coupon|null $coupon
 * @property-read \App\Models\Currency $currency
 * @property-read \App\Models\Customer $customer
 * @property-read int $total
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItem[] $order_items
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StoreOrder[] $store_orders
 * @property-read int|null $store_orders_count
 * @method static \Database\Factories\OrderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $payment_type
 * @property string|null $payment_id
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentType($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderStatus[] $order_statusues
 * @property-read int|null $order_statusues_count
 * @property string|null $ip
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereIp($value)
 */
class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'order_date' => 'datetime',
    ];

    public const  UNIITIATED_ID = 0;
    public const  CONFIRMED_ID = 1;
    public const  PAID_ID = 2;
    public const  READY_ID = 3;
    public const  SHIPPED_ID = 4;
    public const  DELIVERED_ID = 5;
    public const  CANCEL_REQUESTED_ID = 6;
    public const  CANCELLED_ID = 7;
    public const  RETURN_REQUESTED_ID = 8;
    public const  DELIVERY_FAILURE_ID = 9;
    public const RETURN_ACCEPTED_ID = 10;
    public const TERMINATED_ID = 11;
    public const  PAYU = 'payu';
    public const  MYFATOORAH = 'myfatoorah';
    public const INVOICEID = 'invoiceId';
    public const PAYMENTID = 'paymentId';
    
    public const ARCHIVED = 1;
    public const NOTARCHIVED = 0;

    /*private static $orderStatusButton = [
        self::UNIITIATED_ID => [self::CONFIRMED_ID => 'Confirmed'],
        self::CONFIRMED_ID => [self::PAID_ID => 'Paid'],
        self::PAID_ID => [self::READY_ID => 'Ready To Ship', self::CANCEL_REQUESTED_ID => 'Cancellation Requested'],
        self::READY_ID => [self::SHIPPED_ID => 'Shipped', self::CANCEL_REQUESTED_ID => 'Cancellation Requested'],
        self::SHIPPED_ID => [self::DELIVERED_ID => 'Delivered', self::DELIVERY_FAILURE_ID => 'Delivery Failure', self::RETURN_REQUESTED_ID => 'Return Requested'],
        self::DELIVERED_ID => [self::RETURN_REQUESTED_ID => 'Return Requested'],
        self::CANCEL_REQUESTED_ID => [self::CANCELLED_ID => 'Cancelled'],
        self::CANCELLED_ID => [],
        self::RETURN_REQUESTED_ID => [],
        self::DELIVERY_FAILURE_ID => [],

    ];*/ 
  



    private static $statusTransition = [
        self::UNIITIATED_ID => [self::CONFIRMED_ID],
        self::CONFIRMED_ID => [self::PAID_ID],
        self::PAID_ID => [self::READY_ID, self::CANCEL_REQUESTED_ID],
        self::READY_ID => [self::SHIPPED_ID, self::CANCEL_REQUESTED_ID],
        self::SHIPPED_ID => [self::DELIVERED_ID, self::DELIVERY_FAILURE_ID, self::RETURN_REQUESTED_ID],
        self::DELIVERED_ID => [self::RETURN_REQUESTED_ID],
        self::CANCEL_REQUESTED_ID => [self::CANCELLED_ID],
        self::CANCELLED_ID => [],
        self::RETURN_REQUESTED_ID => [],
        self::DELIVERY_FAILURE_ID => [],
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

    private static $statusString = [
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
        self::TERMINATED_ID => 'Terminated',
    ];

    public static $orderStatusButton = [
        self::TERMINATED_ID => [],
        self::UNIITIATED_ID => ['Confirmed'],
        self::CONFIRMED_ID => ['Track', 'Cancel'],
        self::PAID_ID => ['Cancel' , 'Track'],
        self::READY_ID => ['Cancel'],
        self::SHIPPED_ID => ['Track'],
        self::DELIVERED_ID => ['Track', 'Return', 'Feedback'],
        self::CANCEL_REQUESTED_ID => [],
        self::CANCELLED_ID => ['view-cancel'],
        self::RETURN_REQUESTED_ID => ['Track', 'Return'],
        self::DELIVERY_FAILURE_ID => ['Track', 'Return'],
    ];
    
    private static $orderStatusButtonAdmin = [
        self::TERMINATED_ID => [],
        self::UNIITIATED_ID => [self::CONFIRMED_ID => 'Confirmed',self::TERMINATED_ID => 'Terminated'],
        self::CONFIRMED_ID => [self::PAID_ID => 'Paid',self::TERMINATED_ID => 'Terminated'],
        self::PAID_ID => [self::READY_ID => 'Ready To Ship'],
        self::READY_ID => [self::SHIPPED_ID => 'Shipped'],
        self::SHIPPED_ID => [self::DELIVERED_ID => 'Delivered'], // TODO this needs to be removed when delivery company API is in place
        self::DELIVERED_ID => [],
        self::CANCEL_REQUESTED_ID => [self::CANCELLED_ID => 'Cancelled',self::SHIPPED_ID => 'Shipped'],
        self::CANCELLED_ID => [],
        self::RETURN_REQUESTED_ID => [],
        self::DELIVERY_FAILURE_ID => [],
    ];
    
    public function getPossibleStatusButtonAdmin()
    {
        return self::$orderStatusButtonAdmin[$this->status];
    }


    public function getAllStatus()
    {
        return self::$statusString;
    }



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function store_orders()
    {
        return $this->hasMany(StoreOrder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_statusues()
    {
        return $this->hasMany(OrderStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    /**
     * Get all of the items for the order.
     */
    public function order_items()
    {
        return $this->hasManyThrough(OrderItem::class, StoreOrder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function return_requests()
    {
        return $this->hasManyThrough(ReturnRequest::class, StoreOrder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cancel_requests()
    {
        return $this->hasManyThrough(CancelRequest::class, StoreOrder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cancel_request()
    {
        return $this->hasOneThrough(CancelRequest::class, StoreOrder::class);
    }

    /**
     * @param int $newStatus
     * @return $this
     * @throws \Exception
     */
    public function changeStatus(int $newStatus)
    {
        if (!isset(self::$statusTransition[$newStatus])) {
            throw new \Exception('Invalid New Order Status');
        }

        $possibleTransition = self::$statusTransition[$this->status];
        if (!in_array($newStatus, $possibleTransition)) {
            throw new \Exception("Invalid Order Status Transition {$this->status} to $newStatus");
        }

        $this->status = $newStatus;
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function toConfirm()
    {
        $this->changeStatus(Order::CONFIRMED_ID);
        /** @var StoreOrder $storeOrder */
        foreach ($this->store_orders as $storeOrder) {
            if($storeOrder->status == StoreOrder::UNIITIATED_ID){
                $storeOrder->toConfirm();
            }
            $storeOrder->save();
        }
        $this->save();
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function toPaid()
    {
        $this->changeStatus(Order::PAID_ID);
        /** @var StoreOrder $storeOrder */
        foreach ($this->store_orders as $storeOrder) {
            $storeOrder->toPaid();
            $storeOrder->save();
        }
        $this->save();
        return $this;
    }
    /**
     * @return $this
     * @throws \Exception
     */
    public function toCancel()
    {
        $this->changeStatus(Order::CANCELLED_ID);
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalAttribute()
    {
        return $this->sub_total + $this->delivery_fee  + $this->tax - $this->getDiscount();
    }

    /**
     * @return int
     */
    public function getTotalQty()
    {
        return $this->order_items->sum('quantity');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return self::$statusString[$this->status];
    }

    public function getPossibleStatusButton()
    {
        return self::$orderStatusButton[$this->status];
    }

    public function getTrackStatus()
    {
        return self::$orderTrackStatus[$this->status];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'customer_id' => $this->customer_id,
            'address_id' => $this->address_id,
            'sub_total' => $this->sub_total,
            'delivery_fee' => $this->delivery_fee,
            'tax' => $this->tax,
            'coupon_id' => $this->coupon_id,
            'currency_id' => $this->currency_id,
            'address' => $this->address,
            'order_date' => $this->order_date,
            'store_orders' => $this->store_orders->toArray()
        ];
    }
    
    public function allStoreOrderItemsRequestedForCancellation() : bool
    {
        /** @var StoreOrder $storeOrder */
        foreach ($this->order_items as $orderItems) {
            if ($orderItems->status !== OrderItem::CANCEL_REQUESTED_ID) {
                return false;
            }
        }
        return true;
    }
    
    public function allStoreOrderRequestedForCancellation() : bool
    {
        /** @var StoreOrder $storeOrder */
        foreach ($this->store_orders as $storeOrder) {
            if ($storeOrder->status !== StoreOrder::CANCEL_REQUESTED_ID) {
                return false;
            }
        }
        return true;
    }

    public function allStoreOrderCancelled() : bool
    {
        /** @var StoreOrder $storeOrder */
        foreach ($this->store_orders as $storeOrder) {
            if ($storeOrder->status !== StoreOrder::CANCELLED_ID) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * @return Carbon
     */
    public function getLastStatusUpdateDate()
    {
        // TODO this need to be implemented
        return $this->order_date;
    }

    public function canCancellable()
    {
        if($this->order_date < Carbon::parse('-24 hours')) {
            return false;
        }
        return true;
    }

    public function customerAddresses()
    {
        return $this->hasOne(CustomerAddress::class, 'id','address_id')->withTrashed();
    }

    public function isDiscountApplied()
    {
        if ($this->discount != null && $this->discount_type != null) {
            return true;
        }
        return false;
    }

    public function getDiscount()
    {
        $discount = 0;
        foreach ($this->order_items as $order_item) {
            $discount += $order_item->discount;
        }
        return $discount;
    }

    /*public function getDiscountedAmount()
    {
        if ($this->discount_type === 'fixed') {
            return $this->discount;
        }

        $discounted_amount = $this->sub_total * ($this->discount / 100);
        return $discounted_amount;
    }*/
}
