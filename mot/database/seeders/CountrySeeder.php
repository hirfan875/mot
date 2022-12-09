<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Service\CountryService;

class CountrySeeder extends Seeder
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
            'title' => 'Turkey',
            'code' => 'TR'
        ];

        $countryService = new CountryService();
        $countryService->create($request);
    }
}
