<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->tinyInteger('status')->nullable();
            $table->string('banner')->nullable();
            $table->longText('description')->nullable();
            $table->longText('return_and_refunds')->nullable();
            $table->longText('policies')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_data');
    }
}
