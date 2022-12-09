<?php

namespace App\Console\Commands;

use App\Events\ProductPriceUpdate;
use App\Models\FlashDeal;
use Illuminate\Console\Command;

class ExpireFlashDeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:flash-deals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire flash deals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deals = FlashDeal::where(function($query){
                    $query->whereExpired(false)->orWhereNull('expired');
                })->where('ending_at', '<', now())->with('product')->get();
       
        foreach ($deals as $deal) {
            if($deal->product){
            $deal->expired = true;
            $deal->save();
            // dispatch events
                ProductPriceUpdate::dispatch($deal->product);
            }
//            ProductPriceUpdate::dispatch($product);
        }

        $this->info('Command executed successfully.');
        return 0;
    }
}
