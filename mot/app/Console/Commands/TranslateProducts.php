<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Service\TrendyolCategoryService;
use App\Service\ProductService;
use Illuminate\Support\Facades\Http;
use Cache;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductTranslate;
use Google\Cloud\Translate\TranslateClient;

class TranslateProducts extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trendyol:translate-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate products.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {

        $products = Product::with(['product_translate'])->whereHas('product_translates' , function ($q) {
            return $q->whereNull('translated_by');
        })->where('status', true)->whereNotNull('barcode')->get();
        //->whereIn('store_id', [281,275,270,269,264,256,255])
        if ($products) {
            foreach ($products as $row) {
                ini_set('max_execution_time', 0);
                $translate = new TranslateClient(['key' => 'AIzaSyDfrr28mzao7KAh_t0s4caVn-_T6OcT7Rk']);
                foreach ($row->product_translate as $val) {
                    if ($val->language_code != 'tr') {
                        // Translate text from english to french.
                        $title = $translate->translate($row->title, ['target' => $val->language_code]);
                        $data = $translate->translate($row->data, ['target' => $val->language_code]);

                        $productTranslate = ProductTranslate::firstOrNew(['product_id' => $row->id, 'language_code' => $val->language_code]);
                        $productTranslate->title = html_entity_decode($title['text']);
                        $productTranslate->short_description = $data['text'];
                        $productTranslate->data = $data['text'];
                        $productTranslate->meta_title = html_entity_decode($title['text']);
                        $productTranslate->meta_desc = $data['text'];
                        $productTranslate->meta_keyword = html_entity_decode($title['text']);
                        $productTranslate->translated_by = 'google-bot';
                        $productTranslate->save();
                    } else {
                        $productTranslate = ProductTranslate::firstOrNew(['product_id' => $row->id, 'language_code' => $val->language_code]);
                        $productTranslate->translated_by = 'google-bot';
                        $productTranslate->save();
                    }
                }
                
                        $product = Product::firstOrNew(['id' => $row->id]);
                        $additionalInfo = $translate->translate($row->additional_information, ['target' => 'en']);
                        $product->additional_information =  $additionalInfo['text'];
                        $product->save();

            }
            $this->info('Command executed successfully.');
        }
    }

}
