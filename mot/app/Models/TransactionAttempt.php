<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TransactionAttempt
 *
 * @property int $id
 * @property int|null $order_id
 * @property string|null $transacton_request
 * @property string|null $transaction_response
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionAttempt whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionAttempt whereTransactionResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionAttempt whereTransactonRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionAttempt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TransactionAttempt extends Model
{
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    /**
     * @param TransactionAttempt $transactionAttempt
     * @return string
     */
    public function  getConversationId()
    {
        return "1234566".$this->id;
    }

}
