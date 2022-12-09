<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForexRateColumsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('forex_rate', 12, 4)->nullable()->after('currency_id');
            $table->decimal('base_forex_rate', 12, 4)->nullable()->after('currency_id');
            $table->timestamp('forex_update_datetime')->nullable()->after('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('forex_rate');
            $table->dropColumn('base_forex_rate');
            $table->dropColumn('forex_update_datetime');
        });
    }
}
