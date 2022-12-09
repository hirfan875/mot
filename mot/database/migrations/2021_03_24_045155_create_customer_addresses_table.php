<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('name', 75)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zipcode', 15)->nullable();
            $table->string('country', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // set foreign keys
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_addresses');
    }
}
