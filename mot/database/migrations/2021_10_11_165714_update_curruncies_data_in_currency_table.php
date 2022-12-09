<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCurrunciesDataInCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('currencies')->upsert([
            ['id' => 1,  'is_default' => 'Yes',   'title' => 'Turkish Lira',  'base_rate' => '8.901893',  'code' => 'TRY',  'symbol' => '₺',    'symbol_position' => 'left',  'emoji' => '🇹🇷',   'emoji_uc' => 'U+1F1F9 U+1F1F7'],
            ['id' => 2,  'is_default' => null,   'title' => 'Kuwait',         'base_rate' => '0.30163',   'code' => 'KWD',  'symbol' => 'KD',   'symbol_position' => 0,       'emoji' => '🇰🇼',   'emoji_uc' => 'U+1F1F0 U+1F1FC'],
            ['id' => 3,  'is_default' => null,   'title' => 'Saudi Arabia',   'base_rate' => '3.750222',  'code' => 'SAR',  'symbol' => 'SR',   'symbol_position' => 0,       'emoji' => '🇸🇦',   'emoji_uc' => 'U+1F1F8 U+1F1E6'],
            ['id' => 4,  'is_default' => null,   'title' => 'Bahrain',        'base_rate' => '0.376957',  'code' => 'BHD',  'symbol' => 'BD',   'symbol_position' => 0,       'emoji' => '🇧🇭',   'emoji_uc' => 'U+1F1E7 U+1F1ED'],
            ['id' => 5,  'is_default' => null,   'title' => 'UAE',            'base_rate' => '3.67299',   'code' => 'AED',  'symbol' => 'AED',  'symbol_position' => 0,       'emoji' => '🇦🇪',   'emoji_uc' => 'U+1F1E6 U+1F1EA'],
            ['id' => 6,  'is_default' => null,   'title' => 'Qatar',          'base_rate' => '3.64125',   'code' => 'QAR',  'symbol' => 'QR',   'symbol_position' => 0,       'emoji' => '🇶🇦',   'emoji_uc' => 'U+1F1F6 U+1F1E6'],
            ['id' => 7,  'is_default' => null,   'title' => 'Oman',           'base_rate' => '0.385',     'code' => 'OMR',  'symbol' => 'OMR',  'symbol_position' => 0,       'emoji' => '🇴🇲',   'emoji_uc' => 'U+1F1F4 U+1F1F2'],
            ['id' => 8,  'is_default' => null,   'title' => 'Jordan',         'base_rate' => '0.709',     'code' => 'JOD',  'symbol' => 'JOD',  'symbol_position' => 0,       'emoji' => '🇯🇴',   'emoji_uc' => 'U+1F1EF U+1F1F4'],
            ['id' => 9,  'is_default' => null,   'title' => 'Europe',         'base_rate' => '0.864408',  'code' => 'EUR',  'symbol' => '€',    'symbol_position' => 0,       'emoji' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿',   'emoji_uc' => 'U+1F3F4 U+E0067']
        ], ['id', 'is_default', 'title', 'base_rate', 'code', 'symbol', 'symbol_position', 'emoji', 'emoji_uc']
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
