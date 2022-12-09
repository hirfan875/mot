<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnRequestGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_request_galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_order_item_id');
            $table->string('image' , 225)->nullable();
            $table->timestamps();

             // set foreign keys
            $table->foreign('return_order_item_id')->references('id')->on('return_order_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_request_galleries');
    }
}
