<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreOrderIdToStoreReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_reviews', function (Blueprint $table) {
            $table->dropForeign('store_reviews_order_item_id_foreign');
            $table->unsignedBigInteger('store_order_id')->after('customer_id');
            $table->foreign('store_order_id')->references('id')->on('store_orders');
        });
        Schema::table('store_reviews', function (Blueprint $table) {
            $table->dropColumn('order_item_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_reviews', function (Blueprint $table) {
            $table->dropForeign('store_reviews_store_order_id_foreign');
            $table->unsignedBigInteger('order_item_id')->after('customer_id');
            $table->foreign('order_item_id')->references('id')->on('order_items');
        });
        Schema::table('store_reviews', function (Blueprint $table) {
            $table->dropColumn('store_order_id');
        });
    }
}
