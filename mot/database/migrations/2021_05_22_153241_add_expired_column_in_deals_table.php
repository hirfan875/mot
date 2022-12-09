<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiredColumnInDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_deals', function (Blueprint $table) {
            $table->tinyInteger('expired')->index()->after('is_approved');
        });

        Schema::table('flash_deals', function (Blueprint $table) {
            $table->tinyInteger('expired')->index()->after('is_approved');
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
            $table->dropColumn('expired');
        });

        Schema::table('flash_deals', function (Blueprint $table) {
            $table->dropColumn('expired');
        });
    }
}
