<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_devices', function (Blueprint $table) {
            $table->integer('is_general_notifications')->default('1')->change();
            $table->integer('is_order_notifications')->default('1')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_devices', function (Blueprint $table) {
            //
        });
    }
}
