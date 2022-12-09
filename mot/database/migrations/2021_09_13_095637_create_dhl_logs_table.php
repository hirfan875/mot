<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhlLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhl_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('store_order_id')->nullable();
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->string('type')->nullable();
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->timestamps();
            
            // set foreign keys
            $table->foreign('store_order_id')->references('id')->on('store_orders')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dhl_logs');
    }
}
