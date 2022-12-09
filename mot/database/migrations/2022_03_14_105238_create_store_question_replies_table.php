<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreQuestionRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_question_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_question_id')->nullable();
            $table->string('subject', 200)->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->foreign('store_question_id')->references('id')->on('store_questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_question_replies');
    }
}
