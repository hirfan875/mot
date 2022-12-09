<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrendyolCategoriesAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trendyol_categories_assign', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trendyol_categories_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();
            
            // set foreign keys
            $table->foreign('trendyol_categories_id')->references('id')->on('trendyol_categories');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trendyol_categories_assign');
    }
}
