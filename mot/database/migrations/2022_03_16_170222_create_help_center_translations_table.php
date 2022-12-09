<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpCenterTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_center_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('help_center_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('language_code')->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            //foreign keys
            $table->foreign('help_center_id')->references('id')->on('help_centers')->onDelete('cascade');
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
        Schema::dropIfExists('help_center_translations');
    }
}
