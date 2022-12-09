<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->text('address2')->nullable();
            $table->text('address3')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zipcode', 15)->nullable();
            $table->string('country', 100)->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('store_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_addresses');
    }
}
