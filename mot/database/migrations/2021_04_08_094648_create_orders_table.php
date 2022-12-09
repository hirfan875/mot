<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // TODO Split these migrations to different migrations for easier management
        // prior to merge to master

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code', 20)->nullable();
            $table->string('status', 20);
            $table->timestamp('start_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20);
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('address_id');
            $table->integer('sub_total');// saved as integer to avoid rounding errors
            $table->integer('delivery_fee');
            $table->integer('tax');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('currency_id');
            $table->text('address');
            $table->timestamp('order_date')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->unique('order_number');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('address_id')->references('id')->on('customer_addresses');
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('currency_id')->references('id')->on('currencies');

        });


        Schema::create('store_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('status');
            $table->unsignedBigInteger('order_id');     // parent
            $table->string('order_number', 20);
            $table->unsignedBigInteger('delivery_fee')->default(0);
            $table->unsignedBigInteger('sub_total')->default(0);
            $table->unsignedBigInteger('mot_fee')->default(0);
            $table->timestamps();
            $table->unique('order_number');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('order_id')->references('id')->on('orders');
        });


        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_order_id');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('quantity');
            $table->bigInteger('unit_price');
            $table->timestamps();

            // set foreign keys
            $table->foreign('store_order_id')->references('id')->on('store_orders');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('order_refunds', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('store_order_id');
            $table->bigInteger('refund_amount');
            $table->bigInteger('refund_type');
            $table->text('notes');
            $table->timestamps();

            // set foreign keys
            $table->foreign('store_order_id')->references('id')->on('store_orders');
        });
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('store_order_id');
            $table->bigInteger('quantity');
            $table->unsignedBigInteger('order_item_id');
            $table->text('notes');
            $table->unsignedBigInteger('tracking_id')->nullable();
            $table->string('company_name' , 50)->nullable();
            $table->timestamps();
            // set foreign keys
            $table->foreign('store_order_id')->references('id')->on('store_orders');
            $table->foreign('order_item_id')->references('id')->on('order_items');
        });
        Schema::create('cancel_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_order_id');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
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

        Schema::dropIfExists('cancel_requests');
        Schema::dropIfExists('return_requests');
        Schema::dropIfExists('order_refunds');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('store_orders');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('couponss');
    }
}
