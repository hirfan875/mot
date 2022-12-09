<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_response', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_inquiry_id')->nullable();
            $table->string('subject', 200)->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            // set foreign keys
            $table->foreign('contact_inquiry_id')->references('id')->on('contact_inquiries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_response');
    }
}
