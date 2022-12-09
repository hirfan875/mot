<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrendingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trending_products', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable();
            $table->string('title', 200)->nullable();
            $table->string('type', 20)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('products_type', 20)->nullable();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->text('view_all_url')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->foreign('tag_id')->references('id')->on('tags')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trending_products');
    }
}
