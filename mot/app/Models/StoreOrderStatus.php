<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreOrderStatus
 *
 * @property int $id
 * @property int $store_order_id
 * @property int $from_status
 * @property int $to_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus whereFromStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus whereStoreOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus whereToStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|StoreOrderStatus whereUserId($value)
 */
class StoreOrderStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
}
