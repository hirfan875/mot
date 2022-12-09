<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmojiUcInLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('languages')->upsert([
            ['id' => 1,     'emoji_uc' => 'gb'],
            ['id' => 8,     'emoji_uc' => 'kw'],
            ['id' => 164,   'emoji_uc' => 'tr']
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
        Schema::dropIfExists('languages');
    }
}
