<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryProductBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_product_banner', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_banner_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            // set foreign keys
            $table->foreign('product_banner_id')->references('id')->on('product_banners')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_product_banner');
    }
}
