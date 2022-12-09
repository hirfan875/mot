<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_order_id')->nullable();
            $table->string('insured_value')->nullable();
            $table->string('weight')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->longText('shiptimestamp')->nullable();
            $table->string('customer_references')->nullable();
            $table->timestamps();
            
            // set foreign keys
            $table->foreign('store_order_id')->references('id')->on('store_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_requests');
    }
}
