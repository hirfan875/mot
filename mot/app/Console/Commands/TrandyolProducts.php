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

class TrandyolProducts extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trendyol:get-products';

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

                $size = 20;
                ini_set('max_execution_time', 0);
                for ($page = 0; $page <= 250; $page++) {
                    
                    $headers = array(
                        'Content-Type:application/json',
                        'Authorization: Basic ' . base64_encode($row->trendyol_key . ":" . $row->trendyol_secret)
                    );
                    $requestUrl = "https://api.trendyol.com/sapigw/suppliers/" . $row->seller_id . "/products?approved=true&page=" . $page . "&size=" . $size;

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

                            $getCategories = TrendyolCategories::with('categoriesAssign')->where('id', $val->pimCategoryId)->first();
                            $getProduct = Product::where('barcode', $val->barcode)->first();

                            if (!$getProduct) {
//                                if($val->onSale){
                                $data = [
                                    'title' => $val->title,
                                    'additional-brand' => $val->brand,
                                    'brandId' => $val->brandId,
                                    'sku' => $productService->generateStoreSku($row->id, $val->productMainId),
                                    'store_sku' => $val->batchRequestId,
                                    'price' => $val->listPrice,
                                    'promo_price' => $val->salePrice,
                                    'categories' => isset($getCategories->categoriesAssign[0]) ? [$getCategories->categoriesAssign[0]->id] : '',
                                    'createdBy' => 'trendyol-auto',
                                    'type' => 'simple',
                                    'stock' => $val->quantity,
                                    'volume' => isset($val->dimensionalWeight) ? $val->dimensionalWeight : null,
                                    'data' => $val->description,
                                    'store' => $row->id,
                                    'meta_title' => null,
                                    'short_description' => null,
                                    'images' => $val->images,
                                    'barcode' => $val->barcode,
                                    'vat_rate' => $val->vatRate,
                                    'stock_code' => isset($val->stockCode) ? $val->stockCode : null,
                                    'status' => $val->onSale,
                                    'additional_information' => $val->attributes,
                                    'trendyol_category' => $val->categoryName,
                                    'trendyol_categories_id' => $val->pimCategoryId,
                                ];

                                $productService->trendyolCreate($data);
//                                }
                            } else {
                                $data = [
                                    'title' => $val->title,
                                    'additional-brand' => $val->brand,
                                    'brandId' => $val->brandId,
                                    'sku' => $productService->generateStoreSku($row->id, $val->productMainId),
                                    'store_sku' => $val->batchRequestId,
                                    'price' => $val->listPrice,
                                    'promo_price' => $val->salePrice,
                                    'categories' => isset($getCategories->categoriesAssign[0]) ? [$getCategories->categoriesAssign[0]->id] : '',
                                    'stock' => $val->quantity,
                                    'volume' => isset($val->dimensionalWeight) ? $val->dimensionalWeight : null,
                                    'data' => $val->description,
                                    'store' => $row->id,
                                    'short_description' => null,
                                    'images' => $val->images,
                                    'barcode' => $val->barcode,
                                    'vat_rate' => $val->vatRate,
                                    'stock_code' => isset($val->stockCode) ? $val->stockCode : null,
                                    'status' => $val->onSale,
                                    'additional_information' => $val->attributes,
                                    'trendyol_category' => $val->categoryName,
                                    'trendyol_categories_id' => $val->pimCategoryId,
                                ];

                                $productService->trendyolUpdate($getProduct, $data);
                            }
                        }
                    }
                }
            }
            $this->info('Command executed successfully.');
        }
    }

}
