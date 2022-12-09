<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Service\LanguageService;

class LanguageSeeder extends Seeder
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
            'title' => 'English',
            'native' => 'English',
            'direction' => 'ltr',
            'code' => 'en'
        ];

        $languageService = new LanguageService();
        $languageService->create($request);
    }
}
