<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReturnOrderItems
 *
 * @property int $id
 * @property int $order_item_id
 * @property int $return_request_id
 * @property int $quantity
 * @property string $reason
 * @property string $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OrderItem $order_item
 * @property-read \App\Models\ReturnRequest $returnRequest
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems whereReturnRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnOrderItems whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReturnOrderItems extends Model
{
    use HasFactory;

    protected $fillable = ['order_item_id', 'quantity', 'reason', 'note', 'return_request_id'];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function order_item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id' , 'id');
    }

    public function order_item_transaction()
    {
        return $this->belongsTo(OrderItemTransaction::class, 'order_item_id', 'order_item_id');
    }
}
