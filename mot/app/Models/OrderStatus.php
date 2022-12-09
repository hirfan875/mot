<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderStatus
 *
 * @property int $id
 * @property int $order_id
 * @property int $from_status
 * @property int $to_status
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereFromStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereToStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereUserId($value)
 * @mixin \Eloquent
 */
class OrderStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
}
