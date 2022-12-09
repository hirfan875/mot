<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MyfatoorahSupportedCurrencise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('currencies')->upsert([
            ['id' => 2, 'status' => 1, 'is_default' => null, 'title' => 'Kuwait', 'code' => 'KWD', 'symbol' => 'KD', 'symbol_position' => 0],
            ['id' => 3, 'status' => 1, 'is_default' => null, 'title' => 'Saudi Arabia', 'code' => 'SAR', 'symbol' => 'SR', 'symbol_position' => 0],
            ['id' => 4, 'status' => 1, 'is_default' => null, 'title' => 'Bahrain', 'code' => 'BHD', 'symbol' => 'BD', 'symbol_position' => 0],
            ['id' => 5, 'status' => 1, 'is_default' => null, 'title' => 'UAE', 'code' => 'AED', 'symbol' => 'AED', 'symbol_position' => 0],
            ['id' => 6, 'status' => 1, 'is_default' => null, 'title' => 'Qatar', 'code' => 'QAR', 'symbol' => 'QR', 'symbol_position' => 0],
            ['id' => 7, 'status' => 1, 'is_default' => null, 'title' => 'Oman', 'code' => 'OMR', 'symbol' => 'OMR', 'symbol_position' => 0],
            ['id' => 8, 'status' => 1, 'is_default' => null, 'title' => 'Jordan', 'code' => 'JOD', 'symbol' => 'JOD', 'symbol_position' => 0]
        ], ['id', 'status', 'is_default', 'title', 'code', 'symbol', 'symbol_position']
        );

        DB::table('currencies')->whereIn('id', ['9', '10', '11'])->delete();
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
