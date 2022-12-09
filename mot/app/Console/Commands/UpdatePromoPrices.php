<?php

namespace App\Console\Commands;

use App\Events\ProductPriceUpdate;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Currency;

class UpdatePromoPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:update-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initiate Promo Price Updates Event';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Initiating Promo price Updates.');
        $maxProductId = Product::max('id');
        $id=0;
        while ($id <=  $maxProductId) {
            $product = Product::find($id);
            if ($product) {
                $this->info('Initiating Promo price Updates.'. $product->id . ':' . $product->title);
                ProductPriceUpdate::dispatch($product);
            }
            $id++;
        }
        $this->info('Command executed successfully.');
        return 0;
    }
}
