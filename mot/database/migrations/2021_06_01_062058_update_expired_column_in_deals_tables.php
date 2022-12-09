<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExpiredColumnInDealsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `daily_deals` CHANGE `expired` `expired` TINYINT(4) NULL");
        DB::statement("ALTER TABLE `flash_deals` CHANGE `expired` `expired` TINYINT(4) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `daily_deals` CHANGE `expired` `expired` TINYINT(4) NOT NULL");
        DB::statement("ALTER TABLE `flash_deals` CHANGE `expired` `expired` TINYINT(4) NOT NULL");
    }
}
