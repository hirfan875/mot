<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProfileTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_profile_translates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('language_code')->nullable();
            $table->longText('description')->nullable();
            $table->longText('return_and_refunds')->nullable();
            $table->longText('policies')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            // set foreign keys
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_profile_translates');
    }
}
