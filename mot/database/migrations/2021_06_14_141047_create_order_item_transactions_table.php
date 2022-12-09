<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id'); //             "itemId": "BI24",
            $table->string('paymentTransactionId');
            $table->integer('transactionStatus');
            $table->string('price');
            $table->string('paidPrice');
            $table->string('merchantCommissionRate');
            $table->string('merchantCommissionRateAmount');
            $table->string('iyziCommissionRateAmount');
            $table->string('iyziCommissionFee');
            $table->string('subMerchantPrice');
            $table->string('subMerchantPayoutRate');
            $table->string('subMerchantPayoutAmount');
            $table->string('merchantPayoutAmount');
            $table->string('convertedPaidPrice');
            $table->string('convertedIyziCommissionRateAmount');
            $table->string('convertedIyziCommissionFee');
            $table->string('convertedSubMerchantPayoutAmount');
            $table->string('convertedMerchantPayoutAmount');
            $table->string('convertedIyziConversionRate');
            $table->string('convertedIyziConversionRateAmount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_transactions');
    }
}
