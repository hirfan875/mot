<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title', 200)->nullable();
            $table->string('slug', 200)->nullable()->index();
            $table->string('type', 20)->nullable();
            $table->string('code', 20)->nullable();
            $table->unsignedSmallInteger('sort_order')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('parent_id')->references('id')->on('attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes');
    }
}
