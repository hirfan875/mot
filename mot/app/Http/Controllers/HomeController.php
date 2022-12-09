<?php

namespace App\Http\Controllers;

use App\Models\DailyDeal;
use App\Models\Product;
use App\Service\FilterCategoryService;
use App\Service\HomePageSectionService;
use App\Service\SliderService;
use App\Service\DailyDealService;
use App\Service\FlashDealService;
use Illuminate\Http\Request;
use App\Extensions\Response;
use App\Service\SubscribedUserService;
use App\Service\FilterProductsService;
use App\Service\StoreService;
use App\Models\Store;
use DB;
use App\Models\Attribute;
use App\Models\ProductAttribute;
use App\Models\Category;

class HomeController extends Controller {

    const HOME_PAGE_CATEGORY_LIMIT = 6;

    public function index() {
        $filterCategoryService = new FilterCategoryService();
        $categories = $filterCategoryService->withSubcategories()->active()->take(self::HOME_PAGE_CATEGORY_LIMIT)->get();

        $sliderService = new SliderService();
        $sliders = $sliderService->getHomePageSliders();

        $homepageSectionService = new HomePageSectionService();
        $sections = $homepageSectionService->getSections();

        $dailyDealService = new DailyDealService();
        $deals = $dailyDealService->getDealsForHomePage(8);

        $flashDealService = new FlashDealService();
        $flashDeals = $flashDealService->getDealsForHomePage(8);
        return view('web.home', [
            'currency' => getCurrency(),
            'flashDeals' => $flashDeals,
            'deals' => $deals,
            'sections' => $sections,
            'categories' => $categories,
            'sliders' => $sliders,
        ]);
    }

    public function newsletter(Request $request) {

//        $post_string = '{
//        }';
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, 'https://mallofturkeya.us5.list-manage.com/subscribe/post-json?u=6cf790f44a62588dc0e4becd4&id=5c5f26f180&c=jQuery19008415221768363639_1632727481111&b_6cf790f44a62588dc0e4becd4_5c5f26f180=&subscribe=Subscribe&_=1632727481113&EMAIL='.$request->EMAIL);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($post_string), 'Accept: application/json'));
//        $result1 = curl_exec($ch);
//        $err = curl_error($ch);
//        curl_close($ch);
//        $remove = str_replace('jQuery19008415221768363639_1632727481111(', '', $result1);
//        $json_object = str_replace(')', '', $remove);
//        $json1= json_decode($json_object,true);
//
//        $response = [
//            'result' => 'error',
//            'msg' => 'Unable to Submit Request',
//        ];
//        if(isset($json1['msg']) &&  explode('<a href="https', $json1['msg']) != null){
//            $res = explode(' <a href="https', $json1['result']);
//            $msg = explode(' <a href="https', $json1['msg']);
//
//            $response['result'] = isset($res[0]) ? $res[0] : 'error';
//            $response['msg'] = isset($msg[0]) ? $msg[0] : 'Unable to Submit Request';
//        }
//        return $response;

        $subscribedService = new SubscribedUserService();
        if ($subscribedService->isAlreadySubscribed($request->email)) {
//            return $this->sendError(__($request->email.' this user has already subscribed to our newsletter.'));
        } else {
            $subscribedService->create($request->all());
        }



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.getresponse.com/v3/contacts',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
          "campaign": {
            "campaignId": "rRekU"
          },
          "email": "' . $request->email . '"
        }',
            CURLOPT_HTTPHEADER => array(
                'X-Auth-Token: api-key lolibwrp4nrkrysoi9648b8aici67m2e',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateStoreSku() {
        $products = Product::get();
        foreach ($products as $product) {
            $product->store_sku = $product->sku;
            $product->save();
            $product->sku = $product->store_id . '-' . $product->sku;
            $product->save();
        }

        return response()->json(['success' => true, 'message' => 'Stores Sku has been updated Successfully']);
    }

    public function exportCsv(Request $request) {
        $fileName = 'Stores.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $fileName);

        $tasks = Store::with(['staff', 'store_data', 'store_profile_translates'])->get();

        $columns = array(
            'id',
            'status',
            'name',
            'slug',
            'legal_name',
            'email',
            'social_media',
            'company_website',
            'type',
            'commission',
            'account_title',
            'bank_name',
            'address',
            'city',
            'state',
            'country_id',
            'zipcode',
            'phone',
            'mobile',
            'is_approved',
            'submerchant_key',
            'trendyol_approved',
            'trendyol_secret',
            'trendyol_key',
            'seller_id',
            'goods_services',
            'signature',
            'legal_papers',
            'iban',
            'staff_email',
            'password',
            'banner',
            'logo',
            'description'
        );
        return $this->callback($tasks, $columns);
    }

    public function callback($tasks, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($tasks as $task) {
            $row['id'] = $task->id;
            $row['status'] = $task->status;
            $row['name'] = isset($task->store_profile_translates) ? $task->store_profile_translates->name : $task->name;
            $row['slug'] = $task->slug;
            $row['legal_name'] = $task->legal_name;
            $row['email'] = $task->email;
            $row['social_media'] = $task->social_media;
            $row['company_website'] = $task->company_website;
            $row['type'] = $task->type;
            $row['commission'] = $task->commission;
            $row['account_title'] = $task->account_title;
            $row['bank_name'] = $task->bank_name;
            $row['address'] = $task->address;
            $row['city'] = $task->city;
            $row['state'] = $task->state;
            $row['country_id'] = $task->country_id;
            $row['zipcode'] = $task->zipcode;
            $row['phone'] = $task->phone;
            $row['mobile'] = $task->mobile;
            $row['is_approved'] = $task->is_approved;
            $row['submerchant_key'] = $task->submerchant_key;
            $row['trendyol_approved'] = $task->trendyol_approved;
            $row['trendyol_secret'] = $task->trendyol_secret;
            $row['trendyol_key'] = $task->trendyol_key;
            $row['seller_id'] = $task->seller_id;
            $row['goods_services'] = $task->goods_services;
            $row['signature'] = $task->signature;
            $row['legal_papers'] = $task->legal_papers;
            $row['iban'] = $task->iban;
            $row['staff_email'] = $task->staff[0]->email;
            $row['password'] = $task->staff[0]->password;
            $row['banner'] = isset($task->store_data) ?? $task->store_data->banner;
            $row['logo'] = isset($task->store_data) ?? $task->store_data->logo;
            $row['description'] = isset($task->store_profile_translates) ? $task->store_profile_translates->description : isset($task->store_data) ?? $task->store_data->description;

            fputcsv($file, array(
                $row['id'],
                $row['status'],
                $row['name'],
                $row['slug'],
                $row['legal_name'],
                $row['email'],
                $row['social_media'],
                $row['company_website'],
                $row['type'],
                $row['commission'],
                $row['account_title'],
                $row['bank_name'],
                $row['address'],
                $row['city'],
                $row['state'],
                $row['country_id'],
                $row['zipcode'],
                $row['phone'],
                $row['mobile'],
                $row['is_approved'],
                $row['submerchant_key'],
                $row['trendyol_approved'],
                $row['trendyol_secret'],
                $row['trendyol_key'],
                $row['seller_id'],
                $row['goods_services'],
                $row['signature'],
                $row['legal_papers'],
                $row['iban'],
                $row['staff_email'],
                $row['password'],
                $row['banner'],
                $row['logo'],
                $row['description']
            ));
        }
        fclose($file);
    }

    public function show(Request $request, $slug, FilterProductsService $productService) {
        $fileName = 'Products-' . $slug . '-' . $request['type'] . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $fileName);

        $storeOrders = 0;

        $storeService = new StoreService();
        $store = $storeService->getStore($slug);
        $base_query = $productService->relations(['store', 'gallery', 'attributes', 'variations', 'product_translates', 'categories']);
        $base_query = $base_query->byType($request['type']);
        $base_query = $base_query->byStore($store->id);
        $base_query = $base_query->setActiveFilter();
        $products = $base_query->get();

        $columns = array(
            'id',
            'status',
            'parent_id',
            'brand_id',
            'store_id',
            'is_approved',
            'barcode',
            'trendyol_categories_id',
            'trendyol_category',
            'title',
            'slug',
            'type',
            'sku',
            'store_sku',
            'price',
            'promo_price',
            'promo_source_id',
            'promo_source_type',
            'discount',
            'discount_type',
            'stock',
            'stock_code',
            'image',
            'weight',
            'volume',
            'data',
            'short_description',
            'additional_information',
            'meta_title',
            'meta_desc',
            'created_by',
            'created_by_id',
            'vat_rate',
            'gallery',
            'categories',
            'attributes',
            'Unique Identifier',
            'variations'
        );
        if ($request['type'] == 'variable') {
        return $this->callbackProductsVariable($products, $columns);
        } else {
            return $this->callbackProducts($products, $columns);
        }
    }

    public function callbackProducts($tasks, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        $k = 1;
        foreach ($tasks as $task) {

            $row['id'] = $task->id;
            $row['status'] = $task->status;
            $row['parent_id'] = $task->parent_id;
            $row['brand_id'] = $task->brand_id;
            $row['store_id'] = $task->store_id;
            $row['is_approved'] = $task->is_approved;
            $row['barcode'] = $task->barcode;
            $row['trendyol_categories_id'] = $task->trendyol_categories_id;
            $row['trendyol_category'] = $task->trendyol_category;
            $row['title'] = isset($task->product_translates) ? $task->product_translates->title : $task->title;
            $row['slug'] = $task->slug;
            $row['type'] = $task->type;
            $row['sku'] = $task->sku;
            $row['store_sku'] = $task->store_sku;
            $row['price'] = $task->price;
            $row['promo_price'] = $task->promo_price;
            $row['promo_source_id'] = $task->promo_source_id;
            $row['promo_source_type'] = $task->promo_source_type;
            $row['discount'] = $task->discount;
            $row['discount_type'] = $task->discount_type;
            $row['stock'] = $task->stock;
            $row['stock_code'] = $task->stock_code;
            $row['image'] = isset($task->image) ? 'https://v1.mallofturkeya.com/storage/original/' . $task->image : null;
            $row['weight'] = $task->weight;
            $row['volume'] = $task->volume;
            $row['data'] = isset($task->product_translates) ? $task->product_translates->data : $task->data;
            $row['short_description'] = isset($task->product_translates) ? $task->product_translates->short_description : $task->short_description;
            $row['additional_information'] = $task->additional_information;
            $row['meta_title'] = isset($task->product_translates) ? $task->product_translates->meta_title : $task->meta_title;
            $row['meta_desc'] = isset($task->product_translates) ? $task->product_translates->meta_desc : $task->meta_desc;
            $row['created_by'] = $task->created_by;
            $row['created_by_id'] = $task->created_by_id;
            $row['vat_rate'] = $task->vat_rate;
            $gallery = '';
            foreach ($task->gallery as $key) {
                $gallery .= 'https://v1.mallofturkeya.com/storage/original/' . $key->image . ', ';
            }
            $row['gallery'] = rtrim($gallery, ',');
            $categories = "";
            foreach ($task->categories as $key) {
                $cat = $this->getParentsTree($key, $key->title);
                $categories .= $cat . ' , ';
            }
//             dd($task->categories);
            $row['categories'] =  rtrim($categories, ' , ');
            $attributes = [];
            if ($task->attributes->count() > 0) {
                /* create attribute array */
                $attrGroup = $task->attributes->groupBy('attribute_id');
                $key = 0;
                foreach ($attrGroup as $group) {
                    $attr = Attribute::whereIn('id', $group->pluck('attribute_id'))->first();
                    $options = Attribute::with('attribute_translates')->whereIn('id', $group->pluck('option_id'))->get();
                    $optionArray = [];
                    foreach ($options as $optionKey => $option) {
                        $optionArray[$optionKey]['id'] = $option->id;
                        $optionArray[$optionKey]['title'] = isset($option->attribute_translates) ? $option->attribute_translates->title : $option->title;
                        $optionArray[$optionKey]['slug'] = $option->slug;
                        $optionArray[$optionKey]['code'] = $option->code;
                    }
                    $title = isset($attr->attribute_translates) ? $attr->attribute_translates->title : $attr->title;
                    $attributes[$key]['id'] = $attr->id;
                    $attributes[$key]['title'] = $title;
                    $attributes[$key]['type'] = $attr->type;
                    $attributes[$key]['slug'] = $attr->slug;
                    $attributes[$key]['options'] = $optionArray;

                    $key++;
                }
                $row['attributes'] = json_encode($attributes);
            } else {
                $row['attributes'] = $task->attributes;
            }

            $row['Unique Identifier'] = $k;

            if ($task->attributes->count() > 0) {
                foreach ($task->variations as $varKey => $variation) {
                    $variationOptions = ProductAttribute::where('variation_id', $variation->id)->get();
                    $optionsSlug = Attribute::whereIn('id', $variationOptions->pluck('option_id'))->pluck('slug')->toArray();
                    $variations[$varKey]['id'] = $variation->id;
                    $variations[$varKey]['price'] = (double) $variation->price;
                    $variations[$varKey]['promo_price'] = (double) $variation->promo_price;
                    $variations[$varKey]['stock'] = (int) $variation->stock;
                    $variations[$varKey]['image'] = $variation->image;
                    $variations[$varKey]['slug_string'] = implode("-", $optionsSlug);
                    $variations[$varKey]['slug_array'] = $optionsSlug;
                }
                $row['variations'] = json_encode($variations);
            } else {
                $row['variations'] = $task->variations;
            }

            $k++;

            fputcsv($file, array(
                $row['id'],
                $row['status'],
                $row['parent_id'],
                $row['brand_id'],
                $row['store_id'],
                $row['is_approved'],
                $row['barcode'],
                $row['trendyol_categories_id'],
                $row['trendyol_category'],
                $row['title'],
                $row['slug'],
                $row['type'],
                $row['sku'],
                $row['store_sku'],
                $row['price'],
                $row['promo_price'],
                $row['promo_source_id'],
                $row['promo_source_type'],
                $row['discount'],
                $row['discount_type'],
                $row['stock'],
                $row['stock_code'],
                $row['image'],
                $row['weight'],
                $row['volume'],
                $row['data'],
                $row['short_description'],
                $row['additional_information'],
                $row['meta_title'],
                $row['meta_desc'],
                $row['created_by'],
                $row['created_by_id'],
                $row['vat_rate'],
                $row['gallery'],
                $row['categories'],
                $row['attributes'],
                $row['Unique Identifier'],
                $row['variations']
            ));
        }
        fclose($file);
    }

    public function callbackProductsVariable($tasks, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        $k = 1;

        foreach ($tasks as $task) {

            $row['id'] = $task->id;
            $row['status'] = $task->status;
            $row['parent_id'] = $task->parent_id;
            $row['brand_id'] = $task->brand_id;
            $row['store_id'] = $task->store_id;
            $row['is_approved'] = $task->is_approved;
            $row['barcode'] = $task->barcode;
            $row['trendyol_categories_id'] = $task->trendyol_categories_id;
            $row['trendyol_category'] = $task->trendyol_category;
            $row['title'] = isset($task->product_translates) ? $task->product_translates->title : $task->title;
            $row['slug'] = $task->slug;
            $row['type'] = $task->type;
            $row['sku'] = $task->sku;
            $row['store_sku'] ='';
            $row['price'] = $task->price;
            $row['promo_price'] = $task->promo_price;
            $row['promo_source_id'] = $task->promo_source_id;
            $row['promo_source_type'] = $task->promo_source_type;
            $row['discount'] = $task->discount;
            $row['discount_type'] = $task->discount_type;
            $row['stock'] = $task->stock;
            $row['stock_code'] = $task->stock_code;
            $row['image'] = isset($task->image) ? 'https://v1.mallofturkeya.com/storage/original/' . $task->image : null;
            $row['weight'] = $task->weight;
            $row['volume'] = $task->volume;
            $row['data'] = isset($task->product_translates) ? $task->product_translates->data : $task->data;
            $row['short_description'] = isset($task->product_translates) ? $task->product_translates->short_description : $task->short_description;
            $row['additional_information'] = $task->additional_information;
            $row['meta_title'] = isset($task->product_translates) ? $task->product_translates->meta_title : $task->meta_title;
            $row['meta_desc'] = isset($task->product_translates) ? $task->product_translates->meta_desc : $task->meta_desc;
            $row['created_by'] = $task->created_by;
            $row['created_by_id'] = $task->created_by_id;
            $row['vat_rate'] = $task->vat_rate;
            $gallery = '';
            foreach ($task->gallery as $key) {
                $gallery .= 'https://v1.mallofturkeya.com/storage/original/' . $key->image . ', ';
            }
            $row['gallery'] = rtrim($gallery, ',');
            $categories = "";
            foreach ($task->categories as $key) {
                $cat = $this->getParentsTree($key, $key->title);
                $categories .= $cat . ' , ';
            }
            $row['categories'] = rtrim($categories, ' , ');
            $attributes = [];
//            if ($task->attributes->count() > 0) {
//                /* create attribute array */
//                $attrGroup = $task->attributes->groupBy('attribute_id');
//                $key = 0;
//                foreach ($attrGroup as $group) {
//                    $attr = Attribute::whereIn('id', $group->pluck('attribute_id'))->first();
//                    $options = Attribute::with('attribute_translates')->whereIn('id', $group->pluck('option_id'))->get();
//                    $optionArray = [];
//                    foreach ($options as $optionKey => $option) {
//                        $optionArray[$optionKey]['id'] = $option->id;
//                        $optionArray[$optionKey]['title'] = isset($option->attribute_translates) ? $option->attribute_translates->title : $option->title;
//                        $optionArray[$optionKey]['slug'] = $option->slug;
//                        $optionArray[$optionKey]['code'] = $option->code;
//                    }
//                    $title = isset($attr->attribute_translates) ? $attr->attribute_translates->title : $attr->title;
//                    $attributes[$key]['id'] = $attr->id;
//                    $attributes[$key]['title'] = $title;
//                    $attributes[$key]['type'] = $attr->type;
//                    $attributes[$key]['slug'] = $attr->slug;
//                    $attributes[$key]['options'] = $optionArray;
//
//                    $key++;
//                }
//                $row['attributes'] = json_encode($attributes);
//            } else {
                $row['attributes'] = '';
//            }

            $row['Unique Identifier'] = $k;

//            if ($task->attributes->count() > 0) {
//                foreach ($task->variations as $varKey => $variation) {
//                    $variationOptions = ProductAttribute::where('variation_id', $variation->id)->get();
//                    $optionsSlug = Attribute::whereIn('id', $variationOptions->pluck('option_id'))->pluck('slug')->toArray();
//                    $variations[$varKey]['id'] = $variation->id;
//                    $variations[$varKey]['price'] = (double)$variation->price;
//                    $variations[$varKey]['promo_price'] = (double)$variation->promo_price;
//                    $variations[$varKey]['stock'] = (int)$variation->stock;
//                    $variations[$varKey]['image'] = $variation->image;
//                    $variations[$varKey]['slug_string'] = implode("-", $optionsSlug);
//                    $variations[$varKey]['slug_array'] = $optionsSlug;
//                }
//                $row['variations'] = json_encode($variations);
//            } else {
                $row['variations'] = '';
//            }
            $k++;

            fputcsv($file, array(
                $row['id'],
                $row['status'],
                $row['parent_id'],
                $row['brand_id'],
                $row['store_id'],
                $row['is_approved'],
                $row['barcode'],
                $row['trendyol_categories_id'],
                $row['trendyol_category'],
                $row['title'],
                $row['slug'],
                $row['type'],
                $row['sku'],
                $row['store_sku'],
                $row['price'],
                $row['promo_price'],
                $row['promo_source_id'],
                $row['promo_source_type'],
                $row['discount'],
                $row['discount_type'],
                $row['stock'],
                $row['stock_code'],
                $row['image'],
                $row['weight'],
                $row['volume'],
                $row['data'],
                $row['short_description'],
                $row['additional_information'],
                $row['meta_title'],
                $row['meta_desc'],
                $row['created_by'],
                $row['created_by_id'],
                $row['vat_rate'],
                $row['gallery'],
                $row['categories'],
                $row['attributes'],
                $row['Unique Identifier'],
                $row['variations']
            ));

            if ($task->variations->count() > 0) {


                foreach ($task->variations as $varKey => $variation) {

                    $row['id'] = $variation->id;
                    $row['status'] = $task->status;
                    $row['parent_id'] = $task->id;
                    $row['brand_id'] = $task->brand_id;
                    $row['store_id'] = $task->store_id;
                    $row['is_approved'] = $task->is_approved;
                    $row['barcode'] = $task->barcode;
                    $row['trendyol_categories_id'] = $task->trendyol_categories_id;
                    $row['trendyol_category'] = $task->trendyol_category;
                    $row['title'] = isset($task->product_translates) ? $task->product_translates->title : $task->title;
                    $row['slug'] = $task->slug;
                    $row['type'] = $task->type;
                    $row['sku'] = $variation->sku;
                    $row['store_sku'] = $task->sku;
                    $row['price'] = (double)$variation->price;
                    $row['promo_price'] = (double)$variation->promo_price;
                    $row['promo_source_id'] = $task->promo_source_id;
                    $row['promo_source_type'] = $task->promo_source_type;
                    $row['discount'] = $task->discount;
                    $row['discount_type'] = $task->discount_type;
                    $row['stock'] = (int)$variation->stock;
                    $row['stock_code'] = $task->stock_code;
                    $row['image'] = isset($variation->image) ? 'https://v1.mallofturkeya.com/storage/original/' . $variation->image : null;
                    $row['weight'] = $task->weight;
                    $row['volume'] = $task->volume;
                    $row['data'] = isset($task->product_translates) ? $task->product_translates->data : $task->data;
                    $row['short_description'] = isset($task->product_translates) ? $task->product_translates->short_description : $task->short_description;
                    $row['additional_information'] = $task->additional_information;
                    $row['meta_title'] = isset($task->product_translates) ? $task->product_translates->meta_title : $task->meta_title;
                    $row['meta_desc'] = isset($task->product_translates) ? $task->product_translates->meta_desc : $task->meta_desc;
                    $row['created_by'] = $task->created_by;
                    $row['created_by_id'] = $task->created_by_id;
                    $row['vat_rate'] = $task->vat_rate;
                    $gallery = '';
                    foreach ($task->gallery as $key) {
                        $gallery .= 'https://v1.mallofturkeya.com/storage/original/' . $key->image . ', ';
                    }
                    $row['gallery'] = rtrim($gallery, ',');
                    $categories = "";
                    foreach ($task->categories as $key) {
                        $cat = $this->getParentsTree($key, $key->title);
                        $categories .= $cat . ' , ';
                    }
                    $row['categories'] = rtrim($categories, ' , ');
                    $attributes = [];
                    if ($task->attributes->count() > 0) {
                        /* create attribute array */
                        $attrGroup = $task->attributes->groupBy('attribute_id');
                        $key = 0;
                        foreach ($attrGroup as $group) {
                            $attr = Attribute::whereIn('id', $group->pluck('attribute_id'))->first();
                            $options = Attribute::with('attribute_translates')->whereIn('id', $group->pluck('option_id'))->get();
                            $optionArray = [];
                            foreach ($options as $optionKey => $option) {
                                $optionArray[$optionKey]['id'] = $option->id;
                                $optionArray[$optionKey]['title'] = isset($option->attribute_translates) ? $option->attribute_translates->title : $option->title;
                                $optionArray[$optionKey]['slug'] = $option->slug;
                                $optionArray[$optionKey]['code'] = $option->code;
                            }
                            $title = isset($attr->attribute_translates) ? $attr->attribute_translates->title : $attr->title;
//                            $attributes[$key]['id'] = $attr->id;
                            $attributes[$key]['title'] = $title;
//                            $attributes[$key]['type'] = $attr->type;
//                            $attributes[$key]['slug'] = $attr->slug;
//                            $attributes[$key]['options'] = $optionArray;

                            $key++;
                        }
                        $row['attributes'] = json_encode($attributes);
                    } else {
                        $row['attributes'] = '';
                    }

                    $row['Unique Identifier'] = $k;


                    $variationOptions = ProductAttribute::where('variation_id', $variation->id)->get();
                    $optionsSlug = Attribute::whereIn('id', $variationOptions->pluck('option_id'))->pluck('title')->toArray();
 
                    $row['variations'] = implode("-", $optionsSlug);
                    $k++;

                    fputcsv($file, array(
                        $row['id'],
                        $row['status'],
                        $row['parent_id'],
                        $row['brand_id'],
                        $row['store_id'],
                        $row['is_approved'],
                        $row['barcode'],
                        $row['trendyol_categories_id'],
                        $row['trendyol_category'],
                        $row['title'],
                        $row['slug'],
                        $row['type'],
                        $row['sku'],
                        $row['store_sku'],
                        $row['price'],
                        $row['promo_price'],
                        $row['promo_source_id'],
                        $row['promo_source_type'],
                        $row['discount'],
                        $row['discount_type'],
                        $row['stock'],
                        $row['stock_code'],
                        $row['image'],
                        $row['weight'],
                        $row['volume'],
                        $row['data'],
                        $row['short_description'],
                        $row['additional_information'],
                        $row['meta_title'],
                        $row['meta_desc'],
                        $row['created_by'],
                        $row['created_by_id'],
                        $row['vat_rate'],
                        $row['gallery'],
                        $row['categories'],
                        $row['attributes'],
                        $row['Unique Identifier'],
                        $row['variations']
                    ));
                }
            }
        }

        fclose($file);
    }
    
    public function getParentsTree($category, $name)
    {
        if ($category->parent_id == null)
        {
            return $name;
        }

        $parent = Category::find($category->parent_id);
        $name = $parent->title . ' > ' . $name;

        return $this->getParentsTree($parent, $name);
    }   



}
