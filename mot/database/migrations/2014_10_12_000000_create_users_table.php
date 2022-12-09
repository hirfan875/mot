<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->string('name', 50)->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 200)->nullable()->index();
            $table->rememberToken();
            $table->string('image', 255)->nullable();
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
        Schema::dropIfExists('users');
    }
}
