<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemoveOrderItemIdFromReturnRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_item_id');
        });
        Schema::table('return_requests', function (Blueprint $table) {
//            $table->dropColumn('order_item_id');
            $table->dropColumn('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('order_item_id');
            $table->integer('quantity');
            $table->foreign('order_item_id')->references('id')->on('order_items');
        });
    }
}
