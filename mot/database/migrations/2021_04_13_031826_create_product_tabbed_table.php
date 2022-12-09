<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTabbedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabbed_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tabbed_section_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            // set foreign keys
            $table->foreign('tabbed_section_id')->references('id')->on('tabbed_sections')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabbed_products');
    }
}
