<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('title', 100)->nullable();
            $table->tinyInteger('type')->nullable();
            $table->decimal('delivery_fee_product', 10, 2)->nullable();
            $table->decimal('delivery_fee_sewing', 10, 2)->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
