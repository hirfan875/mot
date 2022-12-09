<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_translates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('language_code')->nullable();
            $table->longText('title')->nullable();
            $table->longText('type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            // set foreign keys
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
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
        Schema::dropIfExists('attribute_translates');
    }
}
