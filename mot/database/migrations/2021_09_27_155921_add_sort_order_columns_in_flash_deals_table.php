<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortOrderColumnsInFlashDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flash_deals', function (Blueprint $table) {
           $table->string('sort_order',50)->nullable()->after('id');
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
            $table->dropColumn('sort_order');
        });
    }
}
