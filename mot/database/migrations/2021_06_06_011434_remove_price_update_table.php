<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePriceUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->unsignedBigInteger('discount_source')->nullable();
            $table->integer('discount_source_type')->nullable();
            $table->index('price' , 'price_index');
        });
       // Schema::dropIfExists('product_prices');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('discount_source', 100)->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });


        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('price_index');
            $table->dropColumn('discounted_price');
            $table->dropColumn('discount_source');
            $table->dropColumn('discount_source_type');
        });


    }
}
