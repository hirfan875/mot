<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderItemTransaction
 *
 * @property int $id
 * @property int $order_item_id
 * @property string $paymentTransactionId
 * @property int $transactionStatus
 * @property string $price
 * @property string $paidPrice
 * @property string $merchantCommissionRate
 * @property string $merchantCommissionRateAmount
 * @property string $iyziCommissionRateAmount
 * @property string $iyziCommissionFee
 * @property string $subMerchantPrice
 * @property string $subMerchantPayoutRate
 * @property string $subMerchantPayoutAmount
 * @property string $merchantPayoutAmount
 * @property string $convertedPaidPrice
 * @property string $convertedIyziCommissionRateAmount
 * @property string $convertedIyziCommissionFee
 * @property string $convertedSubMerchantPayoutAmount
 * @property string $convertedMerchantPayoutAmount
 * @property string $convertedIyziConversionRate
 * @property string $convertedIyziConversionRateAmount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OrderItem $order_item
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereConvertedIyziCommissionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereConvertedIyziCommissionRateAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereConvertedIyziConversionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereConvertedIyziConversionRateAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereConvertedMerchantPayoutAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereConvertedPaidPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereConvertedSubMerchantPayoutAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereIyziCommissionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereIyziCommissionRateAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereMerchantCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereMerchantCommissionRateAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereMerchantPayoutAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction wherePaidPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction wherePaymentTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereSubMerchantPayoutAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereSubMerchantPayoutRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereSubMerchantPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereTransactionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItemTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderItemTransaction extends Model
{
    use HasFactory;

    public function order_item()
    {
        return $this->belongsTo(OrderItem::class);
    }

}
