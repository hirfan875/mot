<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryFeeToOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->bigInteger('delivery_fee')->default(0);
            $table->bigInteger('exchange_rate')->default(1);
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign('order_items_currency_id_foreign');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('currency_id');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('delivery_fee');
            $table->dropColumn('currency_id');
            $table->dropColumn('exchange_rate');
        });
    }
}
