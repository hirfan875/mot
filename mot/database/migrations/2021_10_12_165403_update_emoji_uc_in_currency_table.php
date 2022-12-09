<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmojiUcInCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('currencies')->upsert([
            ['id' => 1,   'emoji_uc' => 'tr'],
            ['id' => 2,   'emoji_uc' => 'kw'],
            ['id' => 3,   'emoji_uc' => 'sa'],
            ['id' => 4,   'emoji_uc' => 'bh'],
            ['id' => 5,   'emoji_uc' => 'ae'],
            ['id' => 6,   'emoji_uc' => 'qa'],
            ['id' => 7,   'emoji_uc' => 'om'],
            ['id' => 8,   'emoji_uc' => 'jo'],
            ['id' => 9,   'emoji_uc' => 'eu']
        ], ['id', 'emoji_uc']
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
