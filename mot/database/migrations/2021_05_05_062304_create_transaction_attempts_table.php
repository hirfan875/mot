<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_attempts', function (Blueprint $table) {
            $table->id()->from(100000);
            $table->unsignedBigInteger('order_id')->nullable();
            $table->longText('transacton_request')->nullable();
            $table->longText('transaction_response')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_attempts');
    }
}
