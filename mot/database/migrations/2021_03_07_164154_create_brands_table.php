<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->string('title', 200)->nullable();
            $table->string('slug', 200)->nullable()->index();
            $table->string('image', 255)->nullable();
            $table->longText('data')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_desc')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->unsignedSmallInteger('sort_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
}
