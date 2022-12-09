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
use App\Models\TrendyolCategories;

class TrandyolProductsUpdate extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trendyol:update-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get trendyol products.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {

        $stores = Store::where('status', true)->where('trendyol_approved', true)->whereNotNull('seller_id')->get();
        if ($stores) {
            foreach ($stores as $row) {

                $products = Product::whereNotNull('barcode')->pluck('id', 'barcode');
                $perpage = 100;
                $chunks = $products->chunk($perpage);

                $chunks->toArray();

                foreach ($chunks as $rows) {
                    foreach ($rows as $bar => $id) {

                        ini_set('max_execution_time', 0);
                        $headers = array(
                            'Content-Type:application/json',
                            'Authorization: Basic ' . base64_encode($row->trendyol_key . ":" . $row->trendyol_secret)
                        );
                        $requestUrl = "https://api.trendyol.com/sapigw/suppliers/" . $row->seller_id . "/products?barcode=" . $bar;

                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $requestUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => $headers,
                        ));

                        $response = curl_exec($curl);

                        curl_close($curl);
                        $response = json_decode($response);
                        if (isset($response->content)) {
                            $products = $response->content;

                            $productService = new ProductService();
                            $data = [];

                            foreach ($products as $key => $val) {

                                $getProduct = Product::where('barcode', $bar)->first();
                                $data = [
                                    'price' => $val->listPrice,
                                    'promo_price' => $val->salePrice,
                                    'stock' => $val->quantity,
                                    'store' => $row->id,
                                    'barcode' => $val->barcode,
                                    'vat_rate' => $val->vatRate,
                                    'stock_code' => isset($val->stockCode) ? $val->stockCode : null,
                                    'status' => $val->onSale,
                                ];

                                $productService->trendyolUpdateStatus($getProduct, $data);
                            }
                        }
                    }
                }
            }
            $this->info('Command executed successfully.');
        }
    }

}
