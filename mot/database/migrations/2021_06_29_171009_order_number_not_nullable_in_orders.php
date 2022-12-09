<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderNumberNotNullableInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->nullable()->change();
        });

        Schema::table('store_orders', function (Blueprint $table) {
            $table->string('order_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->change();
        });

        Schema::table('store_orders', function (Blueprint $table) {
            $table->string('order_number')->change();
        });
    }
}
