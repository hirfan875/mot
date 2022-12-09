<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('title', 200)->nullable();
            $table->string('slug', 200)->nullable()->index();
            $table->string('type', 20)->nullable();
            $table->string('sku', 50)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->smallInteger('discount')->nullable();
            $table->string('discount_type', 20)->nullable();
            $table->smallInteger('stock')->nullable();
            $table->longText('data')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_desc')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('parent_id')->references('id')->on('products');
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
