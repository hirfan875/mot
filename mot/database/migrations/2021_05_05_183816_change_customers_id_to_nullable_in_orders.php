<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCustomersIdToNullableInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->default(NULL)->change();
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
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();
        });
    }
}
