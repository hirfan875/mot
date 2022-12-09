<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->string('name', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('message');
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
        Schema::dropIfExists('store_questions');
    }
}
