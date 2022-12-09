<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Service\GoogleFeedsService;

class GoogleFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:feeds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate google feeds.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $googleFeedsService = new GoogleFeedsService();
        $currencies = Currency::where('status', true)->get();

        foreach ($currencies as $currency) {
            $filename = "Google Feed {$currency->code}";
            $filename = Str::slug($filename, '_') . '.xml';

            $googleFeedsService->generate($this->getCurrencyLanguage($currency->code), $currency->code, $filename, $currency->emoji_uc);
        }

        $this->info('Command executed successfully.');
    }

    /**
     * Get currency language.
     *
     * @param string $currency
     * @return string
     */
    protected function getCurrencyLanguage(string $currency): string
    {
        if ($currency === 'TRY') {
            return 'tr';
        }

        if ($currency === 'USD' || $currency === 'EUR') {
            return 'en';
        }

        return 'ar';
    }
}
