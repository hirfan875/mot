<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->tinyInteger('rating');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('order_item_id');
            $table->tinyInteger('is_approved')->default(false);
            $table->foreign('order_item_id')->references('id')->on('order_items');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
        });
        Schema::create('store_reviews', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->tinyInteger('rating');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('order_item_id');
            $table->tinyInteger('is_approved')->default(false);
            $table->foreign('order_item_id')->references('id')->on('order_items');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('store_id')->references('id')->on('stores');
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
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('store_reviews');
    }
}
