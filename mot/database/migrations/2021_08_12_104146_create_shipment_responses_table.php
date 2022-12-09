<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('store_order_id')->nullable();
            $table->string('message_time')->nullable();
            $table->string('message_reference')->nullable();
            $table->string('service_invocation_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('label_image_format')->nullable();
            $table->longText('graphic_image')->nullable();
            $table->string('shipment_identification_number_awb')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
            
            // set foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('shipment_responses');
    }
}
