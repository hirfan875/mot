<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyFatoorahTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_fatoorah_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_attempt_id')->unsigned();
            $table->foreign('transaction_attempt_id')->references('id')->on('transaction_attempts')->cascadeOnDelete();
            $table->string('invoiceId')->nullable();
            $table->string('invoiceStatus')->nullable();
            $table->string('invoiceRefrence')->nullable();
            $table->string('invoiceValue')->nullable();
            $table->string('comments')->nullable();
            $table->string('invoiceDisplayValue')->nullable();
            $table->string('paymentGateway')->nullable();
            $table->string('referenceId')->nullable();
            $table->string('trackId')->nullable();
            $table->string('transactionId')->nullable();
            $table->string('paymentId')->nullable();
            $table->string('authorizationId')->nullable();
            $table->string('transactionStatus')->nullable();
            $table->string('currency')->nullable();
            $table->string('error')->nullable();
            $table->string('errorCode')->nullable();
            $table->string('cardNumber')->nullable();
            $table->string('transationValue')->nullable();
            $table->string('customerServiceCharge')->nullable();
            $table->string('dueValue')->nullable();
            $table->string('paidCurrency')->nullable();
            $table->string('paidCurrencyValue')->nullable();
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
        Schema::dropIfExists('my_fatoorah_transactions');
    }
}
