<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_products', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('link')->nullable();
            $table->string('image', 100)->nullable();
            $table->string('store_name', 100)->nullable();
            $table->string('product_type', 20)->nullable();
            $table->text('comment')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('request_products');
    }
}
