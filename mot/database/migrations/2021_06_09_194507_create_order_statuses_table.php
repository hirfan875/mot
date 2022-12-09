<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->smallInteger('from_status');
            $table->smallInteger('to_status');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('order_id')->references('id')->on('orders');
        });

        Schema::table('store_order_statuses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('to_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_statuses');

        Schema::table('store_order_statuses', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
