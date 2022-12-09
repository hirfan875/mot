<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_deals', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->tinyInteger('is_approved')->nullable()->index();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->tinyInteger('discount')->nullable();
            $table->timestamp('starting_at')->nullable();
            $table->timestamp('ending_at')->nullable();
            $table->string('image', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('store_staff')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flash_deals');
    }
}
