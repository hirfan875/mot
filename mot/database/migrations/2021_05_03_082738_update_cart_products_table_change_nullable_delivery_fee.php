<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCartProductsTableChangeNullableDeliveryFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_product', function (Blueprint $table) {
            $table->bigInteger('delivery_fee')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_product', function (Blueprint $table) {
            $table->bigInteger('delivery_fee')->nullable(false)->change();
        });
    }
}
