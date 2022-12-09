<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderItemColumnInCancelrequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cancel_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->foreign('order_item_id')->references('id')->on('order_items')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cancel_requests', function (Blueprint $table) {
            $table->dropColumn('order_item_id');
        });
    }
}
