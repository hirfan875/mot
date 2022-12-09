<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveProductIdFromReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropForeign('product_reviews_product_id_foreign');
        });
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
        });
        \DB::update("UPDATE product_reviews SET `product_id`=1");
        Schema::table('product_reviews', function (Blueprint $table) {
           $table->foreign('product_id', 'product_reviews_product_id_foreign')->references('id')->on('products');
        });
    }
}
