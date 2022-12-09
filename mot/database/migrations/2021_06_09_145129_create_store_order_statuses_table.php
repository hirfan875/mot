<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_order_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_order_id');
            $table->smallInteger('from_status');
            $table->smallInteger('to_status');
            $table->timestamps();

            // set foreign keys
            $table->foreign('store_order_id')->references('id')->on('store_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_order_statuses');
    }
}
