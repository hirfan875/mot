<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackShipmentResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_shipment_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_order_id')->nullable();
            $table->string('awb_number')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('event_code')->nullable();
            $table->string('track_event_desc')->nullable();
            $table->longText('area_code')->nullable();
            $table->string('area_desc')->nullable();
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
        Schema::dropIfExists('track_shipment_responses');
    }
}
