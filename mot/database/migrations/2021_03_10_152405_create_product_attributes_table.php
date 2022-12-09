<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->unsignedBigInteger('option_id')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('variation_id')->references('id')->on('products');
            $table->foreign('attribute_id')->references('id')->on('attributes');
            $table->foreign('option_id')->references('id')->on('attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
