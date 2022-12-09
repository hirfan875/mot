<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSliderTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider_translates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slider_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('language_code',20)->nullable();
            $table->string('image', 200)->nullable();
            $table->string('button_text', 200)->nullable();
            $table->text('button_url')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            // set foreign keys
            $table->foreign('slider_id')->references('id')->on('sliders')->onDelete('cascade');
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
        Schema::dropIfExists('slider_translates');
    }
}
