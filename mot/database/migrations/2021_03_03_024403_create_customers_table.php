<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->string('name', 50)->nullable();
            $table->string('username', 25)->nullable()->unique();
            $table->string('email', 100)->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 200)->nullable()->index();
            $table->string('phone', 20)->nullable();
            $table->string('birthday', 25)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('customers');
    }
}
