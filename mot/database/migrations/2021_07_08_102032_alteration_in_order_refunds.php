<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterationInOrderRefunds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_refunds', function (Blueprint $table) {
            if (Schema::hasColumn('order_refunds', 'store_order_id')) {
                $table->dropForeign(['store_order_id']);
                $table->dropColumn('store_order_id');
            }
            if (!Schema::hasColumn('order_refunds', 'return_order_item_id')) {
                $table->unsignedBigInteger('return_order_item_id')->nullable();
            }
            if (!Schema::hasColumn('order_refunds', 'order_item_id')) {

                $table->unsignedBigInteger('order_item_id')->nullable();
            }
            $table->string('status', 25)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_refunds', function (Blueprint $table) {
            if (!Schema::hasColumn('order_refunds', 'store_order_id')) {
                $table->unsignedBigInteger('store_order_id');
                $table->foreign('store_order_id')->references('id')->on('store_orders');
            }
            if (Schema::hasColumn('order_refunds', 'return_order_item_id')) {
                $table->dropColumn('return_order_item_id');
            }
            if (Schema::hasColumn('order_refunds', 'order_item_id')) {
                $table->dropColumn('order_item_id');
            }
            $table->tinyInteger('status')->default(0)->change();
        });
    }
}
