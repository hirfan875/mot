<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Service\CurrencyService;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $request = [
            'is_default' => 'Yes',
            'title' => 'Turkish Lira',
            'base_rate' => 1,
            'code' => 'TRY',
            'symbol' => '₺',
            'symbol_position' => 'left',
            'thousand_separator' => ',',
            'decimal_separator' => '.'
        ];

        $currencyService = new CurrencyService();
        $currencyService->create($request);
    }
}
