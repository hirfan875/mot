<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsDataTypeOfTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->decimal('total', 12, 4)->change();
            $table->decimal('sub_total', 12, 4)->change();
            $table->decimal('delivery_fee', 12, 4)->change();
        });

        Schema::table('cart_product', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 4)->change();
            $table->decimal('delivery_fee', 12, 4)->change();
            $table->decimal('delivery_rate', 12, 4)->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('commission', 12, 4)->change();
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->decimal('sub_total', 12, 4)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('sub_total', 12, 4)->change();
            $table->decimal('delivery_fee', 12, 4)->change();
            $table->decimal('tax', 12, 4)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 4)->change();
            $table->decimal('delivery_fee', 12, 4)->change();
            $table->decimal('delivery_rate', 12, 4)->change();
            $table->decimal('exchange_rate', 10, 5)->change();
        });

        Schema::table('order_refunds', function (Blueprint $table) {
            $table->decimal('refund_amount', 12, 4)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('promo_price', 12, 4)->change();
            $table->decimal('price', 12, 4)->change();
            $table->decimal('discount', 12, 4)->change();
            $table->decimal('delivery_fee', 12, 4)->change();
            $table->decimal('discounted_price', 12, 4)->change();
        });

        Schema::table('product_prices', function (Blueprint $table) {
            $table->decimal('price', 12, 4)->change();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->decimal('commission', 12, 4)->change();
        });

        Schema::table('store_orders', function (Blueprint $table) {
            $table->decimal('delivery_fee', 12, 4)->change();
            $table->decimal('sub_total', 12, 4)->change();
            $table->decimal('mot_fee', 12, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
