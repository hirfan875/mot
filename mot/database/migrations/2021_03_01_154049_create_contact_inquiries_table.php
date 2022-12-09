<?php

use Facade\Ignition\Tabs\Tab;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->string('name', 50)->nullable();
            $table->string('email', 70)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('subject', 200)->nullable();
            $table->longText('data')->nullable();
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
        Schema::dropIfExists('contact_inquiries');
    }
}
