<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderTransaction
 *
 * @property int $id
 * @property int $transaction_attempt_id
 * @property string $status
 * @property string $locale
 * @property string $token
 * @property string $systemTime
 * @property string $conversationId
 * @property string $price
 * @property string $paidPrice
 * @property int $installment
 * @property string $paymentId
 * @property string $fraudStatus
 * @property string $merchantCommissionRate
 * @property string $merchantCommissionRateAmount
 * @property string $iyziCommissionRateAmount
 * @property string $iyziCommissionFee
 * @property string $cardType
 * @property string $cardAssociation
 * @property string $cardFamily
 * @property string $binNumber
 * @property string $lastFourDigits
 * @property string $basketId
 * @property string $currency
 * @property string $authCode
 * @property string $phase
 * @property string $mdStatus
 * @property string $hostReference
 * @property string $paymentStatus
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereAuthCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereBasketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereBinNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereCardAssociation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereCardFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereCardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereFraudStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereHostReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereInstallment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereIyziCommissionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereIyziCommissionRateAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereLastFourDigits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereMdStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereMerchantCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereMerchantCommissionRateAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction wherePaidPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction wherePhase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereSystemTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereTransactionAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderTransaction extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
