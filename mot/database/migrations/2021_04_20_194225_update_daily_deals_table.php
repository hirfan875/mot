<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDailyDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_deals', function (Blueprint $table) {
            $table->timestamp('start_date')->change();
            $table->timestamp('end_date')->change();
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
            $table->date('start_date')->change();
            $table->date('end_date')->change();
        });
    }
}
