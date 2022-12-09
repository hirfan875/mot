<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Page;

class SitemapController extends Controller
{
    public function index()
    {
        return response()->view('web.sitemap.index')->header('Content-Type', 'text/xml');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getProducts()
    {
        $products = Product::select('id', 'title', 'slug', 'created_at', 'updated_at')->with('categories')->whereNull('parent_id')->whereHas('store', function($query) {
                $query->where('is_approved',true)->where('status',true);
            })->whereStatus(true)->whereIsApproved(true)->get();
    
        foreach ($products as $product){
//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//              CURLOPT_URL => 'https://api.getresponse.com/v3/shops/5V38/products',
//              CURLOPT_RETURNTRANSFER => true,
//              CURLOPT_ENCODING => '',
//              CURLOPT_MAXREDIRS => 10,
//              CURLOPT_TIMEOUT => 0,
//              CURLOPT_FOLLOWLOCATION => true,
//              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//              CURLOPT_CUSTOMREQUEST => 'POST',
//              CURLOPT_POSTFIELDS =>'{
//              "name": "'.$product->title.'",
//
//              "url": "product/'.$product->slug.'/'.$product->slug.'",
//              "vendor": "GetResponse",
//              "externalId": "'.$product->id.'",
//              "categories": [
//
//              ],
//              "variants": [
//                {
//                  "name": "'.$product->title.'",
//                  "url": "'.$product->title.'",
//                  "sku": "'.$product->sku.'",
//                  "price": '.$product->price.',
//                  "priceTax": '.$product->price.',
//
//                  "images": [
//                    {
//                      "src": "'.$product->image.'",
//                      "position": "1"
//                    }
//                  ],
//                  "metaFields": [
//                  ]
//                }
//              ],
//              "metaFields": [
//              ]
//            }',
//              CURLOPT_HTTPHEADER => array(
//                'X-Auth-Token: api-key lolibwrp4nrkrysoi9648b8aici67m2e',
//                'Content-Type: application/json'
//              ),
//            ));
//
//            $response = curl_exec($curl);
//
//            curl_close($curl);
            //echo $response;

        }    
            
        return response()->view('web.sitemap.products', [
            'products' => $products,
        ])->header('Content-Type', 'text/xml');
    }

    public function getCategories()
    {
        $categories = Category::select('id', 'title', 'slug', 'created_at', 'updated_at')->whereStatus(true)->active()->get();
        return response()->view('web.sitemap.categories', [
            'categories' => $categories,
        ])->header('Content-Type', 'text/xml');
    }

    public function getStores()
    {
        $stores = Store::select('id', 'name', 'slug', 'created_at', 'updated_at')->approved()->get();
        return response()->view('web.sitemap.stores', [
            'stores' => $stores,
        ])->header('Content-Type', 'text/xml');
    }

    public function getBrands()
    {
        $brands = Brand::select('id', 'title', 'slug', 'created_at', 'updated_at')->where(['status' => 1, 'is_approved' => 1])->get();
        return response()->view('web.sitemap.brands', [
            'brands' => $brands,
        ])->header('Content-Type', 'text/xml');
    }

    public function getPages()
    {
        $pages = Page::select('id', 'title', 'slug', 'created_at', 'updated_at')->get();
        return response()->view('web.sitemap.pages', [
            'pages' => $pages,
        ])->header('Content-Type', 'text/xml');
    }

    public function getTags()
    {
        $tags = Tag::all();
        return response()->view('sitemap.tags', [
            'tags' => $tags,
        ])->header('Content-Type', 'text/xml');
    }
}
