<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalCurrencyInCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('currencies')->upsert([
            ['id' => 9, 'status' => 1, 'is_default' => null, 'title' => 'Europe', 'code' => 'EUR', 'symbol' => '€', 'symbol_position' => 0],
        ], ['id', 'status', 'is_default', 'title', 'code', 'symbol', 'symbol_position']
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
