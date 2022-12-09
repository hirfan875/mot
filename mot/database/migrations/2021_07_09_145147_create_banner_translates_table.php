<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_translates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('title', 200)->nullable();
            $table->string('image', 200)->nullable();
            $table->string('button_text', 200)->nullable();
            $table->longText('data')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            // set foreign keys
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');
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
        Schema::dropIfExists('banner_translates');
    }
}
