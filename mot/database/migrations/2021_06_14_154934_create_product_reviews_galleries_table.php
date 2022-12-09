<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewsGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews_galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_review_id');
            $table->string('image', 225)->nullable();
            $table->timestamps();
            // set foreign keys
            $table->foreign('product_review_id')->references('id')->on('product_reviews');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_reviews_galleries');
    }
}
