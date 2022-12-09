<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDatetimeColumnsToTimestamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_deals', function (Blueprint $table) {
            $table->timestamp('starting_at')->change();
            $table->timestamp('ending_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_deals', function (Blueprint $table) {
            $table->dateTime('starting_at')->change();
            $table->dateTime('ending_at')->change();
        });
    }
}
