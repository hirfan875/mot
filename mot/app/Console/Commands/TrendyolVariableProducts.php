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
use App\Models\Attribute;
use App\Models\ProductAttribute;
use App\Models\ProductGallery;
use Google\Cloud\Translate\TranslateClient;
use DB;

class TrendyolVariableProducts extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trendyol:variable-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trendyol Variable Products';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {

        $products = Product::select('store_sku')->selectRaw('count(store_sku) as qty')->where('created_by', 'trendyol-auto')
                        ->where('type', 'simple')->whereNotIn('store_id', [245,271])->groupBy('store_sku')->having('qty', '>', 1)->get();

        if ($products) {
            ini_set('max_execution_time', 0);
            
            foreach ($products as $row) {
                $product = Product::where('store_sku', $row->store_sku)->first();
                $new_product = $product->replicate();
                $new_product->sku = $product->sku.'-p';
                $new_product->store_sku = $product->store_sku.'-p' ;
                $new_product->type = 'variable';
                $new_product->created_at = now();
                $new_product->save();
                
                foreach (getLocaleList() as $loc) {
                    $productTranslate = ProductTranslate::where('product_id', $product->id)->where('language_id', $loc->id)->first();
                    if ($productTranslate != null) {
                        $newTask = $productTranslate->replicate();
                        $newTask->product_id = $new_product->id; // the new project_id
                        $newTask->save();
                    }
                }
                
                $productGallery = ProductGallery::where('product_id', $product->id)->get();
                foreach ($productGallery as $gal) {
                    $productGall = ProductGallery::where('id', $gal->id)->first();
                    $newGal = $productGall->replicate();
                    $newGal->product_id = $new_product->id; // the new project_id
                    $newGal->save();
                }

                $productCat = DB::table('category_product')->where('product_id', $product->id)->first();
                if($productCat != null){
                $values = array('product_id' => $new_product->id,'category_id' => $productCat->category_id);
                DB::table('category_product')->insert($values);
                }
                
                $childProduct = Product::where('store_sku', $row->store_sku)->where('created_by', 'trendyol-auto')
                        ->where('type', 'simple')->where('status', true)->get();
                   
                foreach ($childProduct as $child) {

                    $product_child = Product::firstOrNew(['id' => $child->id]);
                    $product_child->parent_id = $new_product->id;
                    $product_child->type = 'variable';
                    $product_child->save();

                    $option = $this->getProductOptions($product_child);
                    if ($option != NULL) {
                        foreach ($option as $k => $r) {
                            if ($k == 'Beden') {
                                $attribute = Attribute::where('title', $r)->first();
                                if ($attribute == null) {
                                    $attribute = new Attribute();
                                    $attribute->status = true;
                                    $attribute->parent_id = 5;
                                    $attribute->title = $r;
                                    $attribute->slug = strtolower(str_replace(" ", "-", str_replace("/", "-", $r)));
                                    $attribute->save();
                                }
                                
                                if ($new_product->id != $product_child->id) {
                                    $variation_attribute = new ProductAttribute();
                                    $variation_attribute->product_id = $new_product->id;
                                    $variation_attribute->variation_id = $product_child->id;
                                    $variation_attribute->attribute_id = 5;
                                    $variation_attribute->option_id = $attribute->id;
                                    $variation_attribute->save();
                                }
                            } 
 
                        }
                    }
                }
            }
            $this->info('Command executed successfully.');
        }
    }

    protected function getProductOptions($row) {

        ini_set('max_execution_time', 0);
        $headers = array(
            'Content-Type:application/json',
            'Authorization: Basic ' . base64_encode($row->store->trendyol_key . ":" . $row->store->trendyol_secret)
        );
        $requestUrl = "https://api.trendyol.com/sapigw/suppliers/" . $row->store->seller_id . "/products?barcode=" . $row->barcode;

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

                $additional_info = array();
                if (!empty($val->attributes)) {
                    foreach ($val->attributes as $info) {
                        $translate = new TranslateClient(['key' => 'AIzaSyDfrr28mzao7KAh_t0s4caVn-_T6OcT7Rk']);
                        $attribute_value = isset($info->attributeValue) ? $info->attributeValue : '';
                        $attribute_value = $translate->translate($attribute_value, ['target' => 'en']);
                        $attribute_name = isset($info->attributeName) ? $info->attributeName : '';
                        $attribute_name = $translate->translate($attribute_name, ['target' => 'en']);
                        $additional_info[$info->attributeName] = $attribute_value['text'];
                    }
                }
            }

            return $additional_info;
        }
    }

}
