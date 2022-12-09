<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('url')->nullable();
            $table->string('ip')->nullable();
            $table->string('country')->nullable();
            $table->string('devices')->nullable();
            $table->string('total')->nullable();
            $table->string('type')->nullable();
            $table->timestamps(); 
            
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_activities');
    }
}
