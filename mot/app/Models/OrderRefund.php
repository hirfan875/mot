<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderRefund
 *
 * @property int $id
 * @property int $status
 * @property int $order_id
 * @property int $refund_amount
 * @property int $refund_type
 * @property string $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund whereRefundType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $store_order_id
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRefund whereStoreOrderId($value)
 * @method static \Database\Factories\OrderRefundFactory factory(...$parameters)
 */
class OrderRefund extends Model
{
    use HasFactory;
    
    const TYPE_CANCELLED = 0;
    const TYPE_REFUND = 1;
    /* different types of refund cases */
    const CANCEL_ENTIRE_ORDER = 'Cancel entire order';
    const CANCEL_STORE_ORDER = 'Cancel store order';
    const REFUND_REQUEST = 'Request of order refund';
}
