<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSponsorCategoriesTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsor_categories_translates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sponsor_category_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('title',20)->nullable();
            $table->string('image', 200)->nullable();
            $table->string('button_text', 200)->nullable();
            $table->text('button_url')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            // set foreign keys
            $table->foreign('sponsor_category_id')->references('id')->on('sponsor_categories')->onDelete('cascade');
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
        Schema::dropIfExists('sponsor_categories_translates');
    }
}
