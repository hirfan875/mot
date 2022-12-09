<?php

namespace App\Models;

use App\Jobs\CancelOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\RefundStoreOrder;
use Monolog\Logger;
use Illuminate\Support\Collection;

/**
 * App\Models\StoreOrder
 *
 * @property int $id
 * @property int $store_id
 * @property int $status
 * @property int $order_id
 * @property string $order_number
 * @property int $delivery_fee
 * @property int $sub_total
 * @property int $mot_fee
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $total
 * @property-read \App\Models\Order $order
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItem[] $order_items
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Store $seller
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder active()
 * @method static \Database\Factories\StoreOrderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereMotFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\CancelRequest|null $cancel_request
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReturnRequest[] $return_requests
 * @property-read int|null $return_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StoreOrderStatus[] $order_statusues
 * @property-read int|null $order_statusues_count
 * @property int $is_archived
 * @property string|null $archived_date
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereArchivedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrder whereIsArchived($value)
 */
class StoreOrder extends Model
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
        self::UNIITIATED_ID => [self::CONFIRMED_ID, self::TERMINATED_ID],
        self::CONFIRMED_ID => [self::PAID_ID, self::TERMINATED_ID],
        self::PAID_ID => [self::READY_ID, self::CANCELLED_ID],
        self::READY_ID => [self::SHIPPED_ID, self::CANCEL_REQUESTED_ID],
        self::SHIPPED_ID => [self::DELIVERED_ID, self::DELIVERY_FAILURE_ID],
        self::DELIVERED_ID => [self::RETURN_REQUESTED_ID],
        self::CANCEL_REQUESTED_ID => [self::SHIPPED_ID, self::CANCELLED_ID],
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

    public static $orderStatusButton = [
        self::TERMINATED_ID => [],
        self::UNIITIATED_ID => ['Terminated'],
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

    private static $orderStatusButtonSeller = [
        self::TERMINATED_ID => [],
        self::UNIITIATED_ID => [self::TERMINATED_ID => 'Terminated'],
        self::CONFIRMED_ID => [],
        self::PAID_ID => [self::READY_ID => 'Ready To Ship'],
        self::READY_ID => [self::SHIPPED_ID => 'Shipped'],
        self::SHIPPED_ID => [self::DELIVERED_ID => 'Delivered'], // TODO this needs to be removed when delivery company API is in place
        self::DELIVERED_ID => [],
        self::CANCEL_REQUESTED_ID => [self::CANCELLED_ID => 'Cancelled', self::SHIPPED_ID => 'Shipped'],  // This Should have a button to move order to CANCELLED_ID
        self::CANCELLED_ID => [],
        self::RETURN_REQUESTED_ID => [],
        self::DELIVERY_FAILURE_ID => [],
    ];

    private static $orderStatusButtonAdmin = [
        self::TERMINATED_ID => [],
        self::UNIITIATED_ID => [self::TERMINATED_ID => 'Terminated'],
        self::CONFIRMED_ID => [self::TERMINATED_ID => 'Terminated'],
        self::PAID_ID => [self::READY_ID => 'Ready To Ship'],
        self::READY_ID => [self::SHIPPED_ID => 'Shipped'],
        self::SHIPPED_ID => [self::DELIVERED_ID => 'Delivered'], // TODO this needs to be removed when delivery company API is in place
        self::DELIVERED_ID => [],
        self::CANCEL_REQUESTED_ID => [self::CANCELLED_ID => 'Cancelled',self::SHIPPED_ID => 'Shipped'],
        self::CANCELLED_ID => [],
        self::RETURN_REQUESTED_ID => [],
        self::DELIVERY_FAILURE_ID => [],
    ];

    public function getPossibleStatusButtonSeller()
    {
        return self::$orderStatusButtonSeller[$this->status];
    }

    public function getPossibleStatusButtonAdmin()
    {
        return self::$orderStatusButtonAdmin[$this->status];
    }

    ///////////////////////////////////
    /// All relations here
    ///////////////////////////////////
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function return_requests()
    {
        return $this->hasMany(ReturnRequest::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cancel_requests()
    {
        return $this->hasMany(CancelRequest::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_statusues()
    {
        return $this->hasMany(StoreOrderStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cancel_request()
    {
        return $this->hasOne(CancelRequest::class);
    }
    
    public function getAllStatus()
    {
        return [
            self::PAID_ID => 'Paid',
            self::READY_ID => 'Ready',
            self::SHIPPED_ID => 'Shipped',
            self::DELIVERED_ID => 'Delivered',
            self::CANCEL_REQUESTED_ID => 'Requested',
            self::CANCELLED_ID => 'Cancelled',
            self::RETURN_REQUESTED_ID => 'CancellationRequested',
            self::DELIVERY_FAILURE_ID => 'DeliveryFailure',
        ];
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

    /**
     * @return Carbon
     */
    public function getLastStatusUpdateDate()
    {
        // TODO this need to be implemented
        return $this->updated_at;
    }

    /**
     * Scope a query to only include active products
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', '>=', self::PAID_ID);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'store_id' => $this->store_id,
            'order_id' => $this->order_id,
            'order_number' => $this->order_number,
            'sub_total' => $this->sub_total,
            'delivery_fee' => $this->delivery_fee,
            'mot_fee' => $this->mot_fee,
            'order_items' => $this->order_items->toArray()
        ];
    }

    public function getTotalAttribute()
    {
        return $this->sub_total  - $this->getDiscount();
    }

    /**
     * update delivery fee
     *
     * @return $this
     */
    public function updateDeliveryFee()
    {
        $total = 0;
        foreach ($this->order_items as $order_item) {
            $total += abs($order_item->delivery_fee * $order_item->quantity);
        }
        $this->delivery_fee = $total;
        return $this;
    }

    /**
     * update delivery fee
     *
     * @return $this
     */
    public function updateStoreDeliveryFee($deliveryRate)
    {
        $total = 0;
        $this->delivery_fee = $deliveryRate;
        return $this;
    }


    /**
     * update delivery fee
     *
     * @return $this
     */
    public function updateDeliveryRate()
    {
        $total = 0;
        foreach ($this->order_items as $order_item) {
            $total += abs($order_item->delivery_rate);
        }
        $this->delivery_rate = $total;
        return $this;
    }


    /**
     * update order sub total
     *
     * @return $this
     */
    public function updateSubTotal()
    {
        $total = 0;
        foreach ($this->order_items as $order_item) {
            // this is discounted price ... Any further discount may come from coupon
            $total += abs($order_item->unit_price * $order_item->quantity);
        }
        $this->sub_total = $total;
        return $this;
    }

    /**
     * change order status
     *
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
            throw new \Exception("Invalid Store Order Status Transition {$this->status} to $newStatus");
        }

        $this->status = $newStatus;
        return $this;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Send an order to CancelRequested Status
     *
     * @return $this
     * @throws \Exception
     */
    public function toCancelRequest($ip)
    {
        $logger = getLogger('cancel-order');
        
        if (in_array(self::CANCELLED_ID, self::$statusTransition[$this->status])) {
            $logger->info('Cancelling store order with status ' .$this->status);
            $this->toCancel($ip);
            return;
        }
        if (!in_array(self::CANCEL_REQUESTED_ID, self::$statusTransition[$this->status])) {
            $logger->info("Unable to CancelRequested store order with status {$this->status}" );
            throw new \Exception("Invalid Status Transition to CancelRequested" . $this->status);
        }
        $logger->info("CancelRequested store order with status {$this->status}" );
        $this->status = self::CANCEL_REQUESTED_ID;
        $this->save();
        if ($this->order->allStoreOrderRequestedForCancellation()) {
            $logger->info("Cancelling parent order.");
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
            $logger->info("Unable to Cancel store order with status {$this->status}" );
            return $this;
        }

        $this->status = self::CANCELLED_ID;
        $this->save();
        
        if ($this->order->allStoreOrderCancelled()) {
            $logger->info("Cancelling order with status {$this->status}"  );
            $this->order->status = Order::CANCELLED_ID;
            $this->order->save();
        }
        $storeOrderCount  = $this->order->store_orders()->count() ;
        $logger->info("Order has {$storeOrderCount} store orders." );

        if (1 == $storeOrderCount) {
            $logger->info("Asking PaymentGW to cancel the order {$this->status} ." );
            CancelOrder::dispatch($this->order,$ip); // we can cancel the order if , there was only one store order.
            // other wise we will have to issue refund
            return $this;
        }
        $logger->info("Issuing Partial Refund of store order with status {$this->status} ." );
        RefundStoreOrder::dispatch($this,$ip); // Since there are multiple store orders under this order , we can only refund
    }
    
    public function allStoreOrderItemsCancelled() : bool
    {
        /** @var StoreOrder $storeOrder */
        foreach ($this->order_items as $orderItems) {
            if ($orderItems->status !== OrderItem::CANCELLED_ID) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return $this
     */
    public function toConfirm()
    {
        $this->changeStatus(self::CONFIRMED_ID);
        return $this;
    }

    public function removeOrderItemsExcept(Collection $orderItems) {
        $orderItemsIds = $orderItems->pluck('id'); ;
        /** OrderItem  $orderItem */
        foreach ($this->order_items as $orderItem){
            if (!in_array($orderItem->id, $orderItemsIds->toArray())){
                $orderItem->delete();
            }
        }
    }

    /**
     * @return $this
     */
    public function toPaid()
    {
        $this->changeStatus(self::PAID_ID);
        return $this;
    }
    /**
     * @return bool
     */
    public function isDelivered() : bool
    {
        return $this->status === self::DELIVERED_ID;
    }

    public function getStoreOrder()
    {
        $store_order = StoreOrder::with('order')->where('order_id' , $this->id)->first();

        return $store_order;
    }

    public function archivableStatus()
    {
        return [
            self::DELIVERED_ID,
            self::CANCELLED_ID,
            self::DELIVERY_FAILURE_ID,
            self::UNIITIATED_ID,
        ];
    }

    public function isReviewable()
    {
        return [
            self::DELIVERED_ID,
            self::CANCELLED_ID,
            self::DELIVERY_FAILURE_ID,
        ];
    }

    public function cancelRequest()
    {
        return $this->hasOne(CancelRequest::class);
    }

    public function shipment_reponse()
    {
        return $this->hasOne(ShipmentResponse::class, 'store_order_id', 'id');
    }

    public function shipment_requests()
    {
        return $this->hasOne(ShipmentRequest::class, 'store_order_id', 'id');
    }

    public function pickup_reponse()
    {
        return $this->hasOne(PickUpResponse::class, 'store_order_id', 'id');
    }

    public function getDiscount()
    {
        $discount = 0;
        foreach ($this->order_items as $order_item) {
            $discount += $order_item->discount;
        }
        return $discount;
    }

}
