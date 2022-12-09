<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickUpResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_up_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_order_id')->nullable();
            $table->string('dispatch_confirmation')->nullable();
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
        Schema::dropIfExists('pick_up_responses');
    }
}
