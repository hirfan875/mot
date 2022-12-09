<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_attempt_id');
            $table->string("status");
            $table->string("locale");
            $table->string("token", 36);
            $table->string("systemTime", 13);
            $table->string("conversationId", 13);
            $table->string("price", 13);
            $table->string("paidPrice", 13);
            $table->boolean("installment");
            $table->string("paymentId", 8);
            $table->string("fraudStatus", 3);
            $table->string("merchantCommissionRate", 8);
            $table->string("merchantCommissionRateAmount", 8);
            $table->string("iyziCommissionRateAmount", 8);
            $table->string("iyziCommissionFee", 13);
            $table->string("cardType", 20);
            $table->string("cardAssociation", 20);
            $table->string("cardFamily", 20);
            $table->string("binNumber", 20);
            $table->string("lastFourDigits", 20);
            $table->string("basketId", 20);
            $table->string("currency", 20);
            $table->string("authCode", 20);
            $table->string("phase", 20);
            $table->string("mdStatus", 20);
            $table->string("hostReference", 20);
            $table->string("paymentStatus", 20);
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
        Schema::dropIfExists('order_transactions');
    }
}
