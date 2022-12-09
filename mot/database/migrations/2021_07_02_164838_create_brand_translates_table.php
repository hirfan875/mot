<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_translates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('language_code')->nullable();
            $table->string('title', 200)->nullable();
            $table->longText('data')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_desc')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            // set foreign keys
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
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
        Schema::dropIfExists('brand_translates');
    }
}
