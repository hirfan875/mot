<?php

namespace App\Console\Commands;

use App\Events\ProductPriceUpdate;
use App\Models\DailyDeal;
use Illuminate\Console\Command;

class ExpireDailyDeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:daily-deals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire daily deals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deals = DailyDeal::whereExpired(false)->where('ending_at', '<', now())->with('product')->get();
        foreach ($deals as $deal) {

            $deal->expired = true;
            $deal->save();

            // dispatch events
            ProductPriceUpdate::dispatch($deal->product);
        }

        $this->info('Command executed successfully.');
        return 0;
    }
}
