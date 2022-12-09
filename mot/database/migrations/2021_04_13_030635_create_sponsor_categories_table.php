<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSponsorCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsor_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sponsor_section_id')->nullable();
            $table->string('title', 100)->nullable();
            $table->string('image', 250)->nullable();
            $table->string('button_text', 100)->nullable();
            $table->text('button_url')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('sponsor_section_id')->references('id')->on('sponsor_sections')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sponsor_categories');
    }
}
