<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToReturnRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->tinyInteger('is_archive')->default(0)->after('store_order_id');
            $table->tinyInteger('received_expected')->default(0)->after('is_archive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropColumn('is_archive');
            $table->dropColumn('received_expected');
        });
    }
}
