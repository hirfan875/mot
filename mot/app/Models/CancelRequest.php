<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CancelRequest
 *
 * @property int $id
 * @property int $status
 * @property int $order_id
 * @property string $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $store_order_id
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest whereStoreOrderId($value)
 * @property string|null $reason
 * @property-read \App\Models\StoreOrder $store_orders
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest whereReason($value)
 * @method static \Database\Factories\CancelRequestFactory factory(...$parameters)
 * @property string|null $ip
 * @method static \Illuminate\Database\Eloquent\Builder|CancelRequest whereIp($value)
 */
class CancelRequest extends Model
{
    use HasFactory;
    
    const PENDING = 0;
    const RESOLVED = 1;

    protected $fillable = ['status', 'store_order_id', 'reason', 'notes'];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function store_order()
    {
        return $this->belongsTo(StoreOrder::class);
    }
}
