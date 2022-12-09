<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Currency;
use Cache;

class GetCurrenciesRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rates:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get currencies exchange rates from API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currency_api = get_option('currency_api');
        if (empty($currency_api)) {
            $this->warn('Please enter your api key in settings section.');
            return;
        }

        $response = Http::get('https://openexchangerates.org/api/latest.json?app_id=' . $currency_api);
        if ($response->successful()) {

            $rates = $response['rates'];
            $currencies = Currency::all();

            foreach ($currencies as $row) {
                Currency::where('id', $row->id)->update(['base_rate' => $rates[$row->code]]);
            }
            
            Cache::put('currencies', Currency::all());
        }

        $this->info('Command executed successfully.');
        return 0;
    }
}
