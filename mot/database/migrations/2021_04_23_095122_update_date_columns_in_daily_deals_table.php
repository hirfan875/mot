<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDateColumnsInDailyDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_deals', function (Blueprint $table) {
            $table->string('image', 255)->nullable()->after('end_date');
            $table->renameColumn('start_date', 'starting_at');
            $table->renameColumn('end_date', 'ending_at');
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
            $table->dropColumn('image');
            $table->renameColumn('starting_at', 'start_date');
            $table->renameColumn('ending_at', 'end_date');
        });
    }
}
