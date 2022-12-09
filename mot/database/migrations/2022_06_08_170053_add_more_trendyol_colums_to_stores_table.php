<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreTrendyolColumsToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('seller_id')->nullable()->after('submerchant_key');
            $table->string('trendyol_key')->nullable()->after('submerchant_key');
            $table->string('trendyol_secret')->nullable()->after('submerchant_key');
            $table->tinyInteger('trendyol_approved')->nullable()->after('submerchant_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('seller_id');
            $table->dropColumn('trendyol_key');
            $table->dropColumn('trendyol_secret');
            $table->dropColumn('trendyol_approved');
        });
    }
}
