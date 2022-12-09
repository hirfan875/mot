<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->bigInteger('total')->nullable();
            $table->bigInteger('sub_total')->nullable();
            $table->bigInteger('currency_id')->nullable();
            $table->timestamp('total_updated_on')->nullable();
            $table->timestamps();
        });

        Schema::create('cart_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->tinyInteger('quantity');
            $table->bigInteger('unit_price');
            $table->bigInteger('delivery_fee');
            $table->bigInteger('currency_id');
            $table->string('message' , 50)->nullable();
            $table->timestamp('price_updated_on')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_product');
        Schema::dropIfExists('carts');
    }
}
