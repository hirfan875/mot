<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLanguageDataInLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        DB::table('languages')->upsert([
            ['id' => 1,  'is_default' => 'Yes',   'emoji' => '🇬🇧',   'emoji_uc' => 'U+1F1EC U+1F1E7'],
            ['id' => 8,  'is_default' => null,    'emoji' => '🇰🇼',   'emoji_uc' => 'U+1F1F0 U+1F1FC'],
            ['id' => 164,  'is_default' => null,    'emoji' => '🇹🇷',   'emoji_uc' => 'U+1F1F9 U+1F1F7']
        ], ['id', 'is_default', 'emoji', 'emoji_uc']
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
