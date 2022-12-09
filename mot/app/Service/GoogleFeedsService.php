<?php

namespace App\Service;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Support\Str;

class GoogleFeedsService
{
    /**
     * Generate xml.
     *
     * @param string $lang
     * @param string $currency
     * @param string $filename
     * @return void
     */
    public function generate(string $lang, string $currency, string $filename, string $countryCode = '')
    {
        $logger = getLogger('generate-google-xml');

        try {
            app()->setLocale($lang);
           
            $xml = '<?xml version="1.0" encoding="utf-8"?>
            <rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
            <channel>
            <title>Mall of Turkeya</title>
            <link>https://mallofturkeya.com</link>
            <description>Mall of Turkeya google feeds</description>';

            $products = Product::query()
                ->where('status', 1)->where('is_approved', 1)
                ->whereNull('parent_id')
                ->whereHas('store', function ($query) {
                    $query->where('status', 1)->where('is_approved', 1);
                })
                ->with(['product_translates', 'categories.category_translates', 'brand.brand_translates'])
                ->get();

            foreach ($products as $product) {

                $product_title = $product->product_translates ? $product->product_translates->title : $product->title;
                $product_data = $product->product_translates ? $product->product_translates->data : $product->data;

                list($categories, $google_categories) = $this->getCategories($product);

                $product_link = route('product', [
                    'slug' => $product->slug,
                    'product' => $product->id,
                    'lang' => $lang,
                    'currency' => $currency,
                ]);

                $xml .= '<item>
                <g:id>' . $product->id . '</g:id>
                <g:title><![CDATA[ ' . \Illuminate\Support\Str::limit($product_title, 150) . ' ]]></g:title>
                <g:description><![CDATA[' . strip_tags(preg_replace('/[\x00-\x1F\x7F]/', '',strtolower($product_data))) . ']]></g:description>
                <g:link><![CDATA[' . $product_link . ']]></g:link>
                <g:image_link><![CDATA[' . $product->product_detail() . ']]></g:image_link>
                <g:product_type><![CDATA[' . $categories . ']]></g:product_type>
                <g:google_product_category><![CDATA[' . $google_categories . ']]></g:google_product_category>
                <g:condition>new</g:condition>
                <g:availability>' . $this->getAvailability($product) . '</g:availability>
                <g:price>' . $this->getPrice($product->promo_price, $currency) . '</g:price>
                <g:shipping>
                    <g:country>'.strtoupper($countryCode).'</g:country>
                    <g:price>' . $currency . ' 0</g:price>
                </g:shipping>   
                <g:brand><![CDATA[' . $this->getBrand($product) . ']]></g:brand>
                    <g:identifier_exists>false</g:identifier_exists>
                    <g:gtin></g:gtin>
                </item>';
            }

            $xml .= '</channel>
            </rss>';
//             $xml = iconv('UTF-8', 'UTF-8//IGNORE', $xml);
            $myfile = fopen("public/{$filename}", "w") or die("Unable to open file!");
            fwrite($myfile, $xml);
        } catch (\Throwable $exception) {
            $logger->critical($exception->getMessage());
            throw $exception;
        }
    }

    /**
     * Get product categories.
     *
     * @param \App\Models\Product $product
     * @return array
     */
    protected function getCategories(Product $product): array
    {
        $categories = '';
        $google_categories = '';

        foreach ($product->categories as $category) {
            $category_title = $category->category_translates ? $category->category_translates->title : $category->title;
            $categories .= ucfirst(strtolower($category_title)) . " > ";

            if (!empty($category->google_category)) {
                $google_categories = ucfirst(strtolower($category->google_category));
            }
        }

        $categories = trim($categories, " > ");
        // $google_categories = trim($google_categories, " > ");

        return [$categories, $google_categories];
    }

    /**
     * Get product brand.
     *
     * @param \App\Models\Product $product
     * @return string
     */
    protected function getBrand(Product $product): string
    {
        if (!$product->brand) {
            return 'Mall of Turkeya';
        }

        return $product->brand->brand_translates ? $product->brand->brand_translates->title : $product->brand->title;
    }

    /**
     * Get product availability.
     *
     * @param \App\Models\Product $product
     * @return string
     */
    protected function getAvailability(Product $product): string
    {
        if ($product->soldOut()) {
            return 'Out of stock';
        }

        return 'in stock';
    }

    /**
     * Get product price.
     *
     * @param float $price
     * @param string $currency
     * @return string
     */
    protected function getPrice(float $price, string $currency_code): string
    {
        $currency = Currency::where('code', $currency_code)->first();
//        $currency_price = number_format(getForexRate($currency) *  $price, 2, '.', '');
        $currency_price = currency_format_feed($price, $currency);

        return str_replace(',', '',$currency_price);
    }
    
}
