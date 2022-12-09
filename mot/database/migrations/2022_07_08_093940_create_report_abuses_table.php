<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportAbusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_abuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->text('device_token')->nullable();
            $table->string('title', 200)->nullable();
            $table->text('detail')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products');
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
        Schema::dropIfExists('report_abuses');
    }
}
