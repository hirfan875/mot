<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryRateColumsToCartProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_product', function (Blueprint $table) {
            $table->string('delivery_rate', 50)->nullable()->after('delivery_fee');
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
            $table->dropColumn('delivery_rate');
        });
    }
}
