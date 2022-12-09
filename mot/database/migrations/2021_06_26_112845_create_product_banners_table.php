<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_banners', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('is_default')->nullable();
            $table->string('banner_1', 250)->nullable();
            $table->text('banner_1_url', 250)->nullable();
            $table->string('banner_2', 250)->nullable();
            $table->text('banner_2_url')->nullable();
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
        Schema::dropIfExists('product_banners');
    }
}
