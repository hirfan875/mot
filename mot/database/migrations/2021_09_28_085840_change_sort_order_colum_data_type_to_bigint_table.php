<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSortOrderColumDataTypeToBigintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flash_deals', function (Blueprint $table) {
            $table->integer('sort_order')->length(20)->default(1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flash_deals', function (Blueprint $table) {
            //
        });
    }
}
