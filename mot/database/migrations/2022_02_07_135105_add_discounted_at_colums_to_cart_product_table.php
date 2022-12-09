<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountedAtColumsToCartProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_product', function (Blueprint $table) {
            $table->string('discounted_at')->nullable()->after('price_updated_on');
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
            $table->dropColumn('discounted_at');
        });
    }
}
