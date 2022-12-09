<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->decimal('weight', 12, 2)->nullable();
            $table->decimal('rate', 12, 2)->nullable();
            $table->string('zone', 50)->nullable();
            $table->string('shipper', 50)->nullable();
            $table->string('is_default', 50)->nullable();
            $table->tinyInteger('status')->nullable();
            
            // set foreign keys
            $table->foreign('country_id')->references('id')->on('countries');
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
        Schema::dropIfExists('shipping_rates');
    }
}
