<?php

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Translation;
use App\Models\Country;
use App\Exception\InvalidCurrencyException;
use App\Helpers\UtilityHelpers;
use App\Service\MoTCartService;
use App\Service\OrderService;
use App\Models\Order;
use App\Service\FilterStoreOrderService;
use App\Models\Customer;
use App\Models\StoreOrder;
use App\Models\Coupon;
use App\Service\CouponDiscountService;
use App\Models\Cart;
use App\Exceptions\InvalidCouponException;



// phone number format
function phoneformat($number)
{
	$number = str_replace(' ', '', $number);
	$number = str_replace('(', '', $number);
	$number = str_replace(')', '', $number);
	$number = str_replace('-', '', $number);
	$number = str_replace('.', '', $number);
	return $number;
}

// get option
function get_option($option_name)
{

	if (empty($option_name)) {
		return;
	}

	$row = App\Models\Setting::where('option_name', $option_name)->first();
	if (!empty($row)) {
		return $row->option_value;
	}
}

// update option
function update_option($option_name, $option_value)
{
	App\Models\Setting::updateOrCreate(
		['option_name' => $option_name],
		['option_value' => $option_value]
	);
}

// delete option
function delete_option($option_name)
{

	App\Models\Setting::where('option_name', $option_name)->delete();
}

// get logger
function getLogger($channelName = 'error', $level = Logger::DEBUG, $filename='logs/app.log'): Logger
{
    $logger = new Logger($channelName);
    $logger->pushHandler(new StreamHandler(storage_path($filename), $level));
    return $logger;
}

/**
 * get sub categories level spaces for dropdown
 *
 * @param int $level
 * @param int $total
 * @param string $type
 * @return string
 */
function getSubcategoryLevel($level = 1, $total = 0, $type = '&nbsp;')
{
	return str_repeat($type, $total);
}

// uses regex that accepts any word character or hyphen in last name
function split_name($name)
{
	$name = trim($name);
	$last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
	$first_name = trim(preg_replace('#' . preg_quote($last_name, '#') . '#', '', $name));
	return array($first_name, $last_name);
}


/**
 * Format a number to relevant currency format
 * @param $number
 * @param Currency|null $currency
 * @return string
 */
function currency_format($number , Currency $currency = null)
{
    if (!$currency) {
        $currency = getCurrency();
    } 
    $thousand_sep = $currency->thousand_sep;
    $decimal_sep = $currency->decimal_sep;
    // use DB later to pick-up this value, we don't have a field for now.
    $decimals = $currency->code == 'KWD' ? 2 : 2;
//    return number_format($number, $decimals, $decimal_sep, $thousand_sep);
    number_format($number, $decimals, $decimal_sep, $thousand_sep);
    // TODO get ltr value for currency as well .. sometime currency symbol is displayed on left .. sometime on right
    return getCurrencyCode() .' '. number_format(getForexRate($currency) *  $number, $decimals, $decimal_sep, $thousand_sep);

}

function currency_format_feed($number , Currency $currency = null)
{
    if (!$currency) {
        $currency = getCurrency();
    } 
    $thousand_sep = "";
    $decimal_sep = $currency->decimal_sep;
    // use DB later to pick-up this value, we don't have a field for now.
    $decimals = $currency->code == 'KWD' ? 2 : 2;
//    return number_format($number, $decimals, $decimal_sep, $thousand_sep);
    number_format($number, $decimals, $decimal_sep, $thousand_sep);
    // TODO get ltr value for currency as well .. sometime currency symbol is displayed on left .. sometime on right
    return $currency->code .' '. number_format(getForexRate($currency) *  $number, $decimals, $decimal_sep, $thousand_sep);

}

/**
* Format a number to relevant numeric format
 * @param $number
 * @param string $country
 * @return string
 */
function decimal_format($number , $country='TR')
{
    return number_format($number, 2, ",", ".");
}

// get Locale list
function getLocaleList() {
    $row = Language::where('status', 1)->get();
    if (!empty($row)) {
        return $row;
    }
}

/**
 * This method is being called from many places , we need to have some caching
 * @return Currency
 */
function getCurrency()
{
    $logger = getLogger('currency');
    $currency = Session::get('currency');
//    dd($currency->base_rate);
    if ($currency) {
        $logger->debug('Currency ' . $currency->code . ' from Session');
        return $currency;
    }
    $currencyID = Session::get('currencyId');
    if ($currencyID) {
        $logger->debug('Currency ID FROM Session ' . $currencyID);
        $currency = Currency::whereid($currencyID)->where('status' , true)->first();
    }
    if (!$currency) {
        $currency = Currency::where('is_default', 'yes')->first();
        $logger->debug('Get Default Currency from DB');
    }
    if ($currency) {
        setCurrency($currency);
        return $currency;
    }
    throw new InvalidCurrencyException('Invalid Currency Setup.');
}

function getCurrencyById($currency_id)
{
    $logger = getLogger('currency');
    $currency = Session::get('currency');
//    dd($currency->base_rate);
    if ($currency) {
        $logger->debug('Currency ' . $currency->code . ' from Session');
        return $currency;
    }
    $currencyID = Session::get('currencyId');
    
    if ($currencyID) {
        $currency = Currency::whereid($currencyID)->where('status' , true)->first();
    } else {
        
        $currency = Currency::whereid($currency_id)->where('status' , true)->first();
    }
    if (!$currency) {
        $currency = Currency::where('is_default', 'yes')->first();
    }
    if ($currency) {
        setCurrency($currency);
        return $currency;
    }
    throw new InvalidCurrencyException('Invalid Currency Setup.');
}

/**
 * This method is being called from many places , we need to have some caching
 * @return float
 */
function getForexRate(Currency $currency,Currency  $baseCurrency=null)
{
    $logger = getLogger('currency');
    $forexRateKey = 'forexRate:'.$currency->code;
    $forexRate = Session::get($forexRateKey);

    if ($forexRate){
        $logger->info('Forex Rate from Session : ' . $forexRate);
        return $forexRate;
    }
    $logger->info('Caclulating Forex Rate : ' . $currency->code);
    
    $forexRate = calculateForexRate($currency, $baseCurrency);
//    Session::put($forexRateKey , null);
    Session::put($forexRateKey, $forexRate);

    return $forexRate;
}

/**
 * @param Currency $currency
 * @param Currency|null $baseCurrency
 * @return float|int
 */
function calculateForexRate(Currency $currency, Currency $baseCurrency = null){
    
    $logger = getLogger('currency');
    if (!$baseCurrency) {
        $logger->info('No Base Supplied : ' . $currency->code . ' We will assume TRY .. but this will be slow.');
        $baseCurrency = Currency::where('code', 'TRY')->first(); // all prices are in this currency
        if (!$baseCurrency) {
            throw new InvalidCurrencyException('TRY currency missing');
        }
    }
    $forexRateTRY = $baseCurrency->base_rate;

    $forexRate = number_format($currency->base_rate, 3) / number_format($forexRateTRY, 3);
    
    return $forexRate;
}
/**
 * @return string
 */
function getCurrencyCode() : string
{
    $currency = getCurrency();
    return $currency->code;
}

/**
 * @param Currency $currency
 */
function setCurrency(Currency $currency)
{
    $logger = getLogger('currency');
//    $logger->debug('Get Default Currency  from DB');
        Session::put('currencyId', $currency->id);
        Session::put('currency', $currency);
        Session::put('currencyCode', $currency->code);
        if(UtilityHelpers::getCartSessionId()){
        $cartService = new MoTCartService(UtilityHelpers::getCartSessionId());
        $cartService->updateCartForexRate(getCurrency());
        $orderService = new OrderService();
        $orderService->updateOrderForexRate(getCurrency());
        }
//        Session::put('forexRate:'. $currency->code , null); // reset it .. so it will be calculated next time.

}


// get Currencies list
function getCurrenciesList() {
    $row = Currency::where('status', 1)->get();
    if (!empty($row)) {
        return $row;
    }
}
// get Local Dir
function getLocalDir($code)
{
    $lang_dir = Language::where('is_default', 'Yes')->where('status', true)->get()->pluck('direction');
    $row = Language::where('code', $code)->first();
    if (!empty($row)) {
        $lang_dir = $row->direction;
    }
    return $lang_dir;
}

// get locale title
function getLocalTitle($code)
{
    $lang_title = Language::where('is_default', 'Yes')->where('status', true)->get()->pluck('title');
    $row = Language::where('code', $code)->first();
    if (!empty($row)) {
        $lang_title = $row->title;
    }
    return $lang_title;
}

function getlanglist($code)
{
    $lang_title = Language::where('is_default', 'Yes')->where('status', true)->first();
    $row = Language::where('code', $code)->first();
    if (!empty($row)) {
        $lang_title = $row;
    }
    return $lang_title;
}


/**
 * @param $url
 * @return string
 *
 */
function cdn_url($url) : string
{
    return config('app.cdn_url') . $url;
}
// get Locale ID
function getLocaleId($code) {
    $language = Language::where('is_default', 'Yes')->where('status', true)->get()->pluck('id');
    $lang_id = $language[0];
    $data = \Cache::get('language_arr');
    if (empty($data)) {
        $data = [];
        $row = Language::where('status', true)->get();
        foreach ($row as $r) {
            $data[$r->code] = $r->id;
        }
        \Cache::put('language_arr', $data, 600);
    }

    if (!empty($data)) {
        if(isset($data[$code])){
            $lang_id = $data[$code];
        }
    }
    return $lang_id;
}

// get Locale ID
function getDefaultLocaleId() {
    $language = Language::where('is_default', 'Yes')->where('status', true)->get()->pluck('id');
    $lang_id = $language[0];
    return $lang_id;
}

if (!function_exists('_t')) {
    function _t($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }

        $translated = Translation::query()->where('key', $key)->where('language_id', getLocaleId(app()->getLocale()))->first();
        if ($translated) {
            return $translated->translate;
        }

        // Pick from default language
//        $translated = Translation::query()->where('key', $key)->where('language_id', getLocaleId('en'))->first();

//        $translation = new Translation;
//        $translation->language_id = getLocaleId(app()->getLocale());
//        $translation->key = $key;
//        $translation->translate = isset($translated->translate) ? $translated->translate : $key ;
//        $translation->state = isset($translated->translate) ? 'default' : 'default';
//        $translation->save();
//
//        if ($translation) {
//            return $translation->translate;
//        }

        return $key;

//        return trans($key, $replace, $locale);
    }
}

// get AvatarCode
function getAvatarCode($name)
{
    $str = explode(" ",$name);
    $fcode = isset($str[0]) ? substr($str[0],0,1) : '' ;
    $lcode = isset($str[1]) ? substr($str[1],0,1) : '' ;
    return $fcode.$lcode;
}

// get Locale ID
function getCountryCode($country) {
    $countryCode = 'TR';
    $language = Country::where('title', $country)->first();
    if($language){
        $countryCode = $language->code;
    }
    return $countryCode;
}


// get currncy in KWD
function currencyInKWD($code ,$amount) {
    $amounts=$amount;

        $kwdCurrency = Currency::where('code', $code)->first();
        $tryCurrency = Currency::where('code', 'TRY')->first();

        $forexRateTRY = $tryCurrency->base_rate;
        $forexRateKWD = $kwdCurrency->base_rate;

        $forexRate = number_format($forexRateKWD, 3) / number_format($forexRateTRY, 3);

        $amounts= $amount * $forexRate;

    return (float) $amounts;
}

// get currncy in TRY
function currencyInTRY($code,$amount) {
    $amounts=$amount;

    if ($code=='EUR'){

        $eurCurrency = Currency::where('code', 'EUR')->first();
        $tryCurrency = Currency::where('code', 'TRY')->first();

        $forexRateTRY = $tryCurrency->base_rate;
        $forexRateEUR = $eurCurrency->base_rate;

        $forexRate =  number_format($forexRateTRY, 3) / number_format($forexRateEUR, 3);

        $amounts= $amount * $forexRate;
    }
    if ($code=='TRY'){

        $amounts= $amount;
    }
    if ($code=='KWD'){

        $kwdCurrency = Currency::where('code', 'KWD')->first();
        $tryCurrency = Currency::where('code', 'TRY')->first();

        $forexRateTRY = $tryCurrency->base_rate;
        $forexRateKWD = $kwdCurrency->base_rate;

        $forexRate = number_format($forexRateTRY, 3) / number_format($forexRateKWD, 3);

       $amounts= $amount * $forexRate;
    }

    if ($code=='USD'){

        $usdCurrency = Currency::where('code', 'USD')->first();
        $tryCurrency = Currency::where('code', 'TRY')->first();

        $forexRateTRY = $tryCurrency->base_rate;
        $forexRateUSD = $usdCurrency->base_rate;

        $forexRate =  number_format($forexRateTRY, 3) / number_format($forexRateUSD, 3);

        $amounts= $amount * $forexRate;
    }

    return (float) $amounts;
}

function convertTryForexRate($amounts, $forex_rate, $base_forex_rate, $code) {

    if($forex_rate != null && $base_forex_rate != null){
        $forexRate =  number_format($forex_rate, 3) / number_format($base_forex_rate, 3);
        $amounts = $amounts * $forexRate;
    }

    return number_format($amounts, 3);
}

function convertForexRateTry($amounts, $forex_rate, $base_forex_rate, $code) {

    if($forex_rate != null && $base_forex_rate != null){
        $forexRate =  number_format($base_forex_rate, 3) / number_format($forex_rate, 3);
        $amounts = $amounts * $forexRate;
    }

    return number_format($amounts, 3);
}

function totalOrdersByCustomerId($customer_id) {

    $query = Order::with('customer');
    $query->where('customer_id', $customer_id);
    $query->select(DB::raw('count(id) as countTotal'));
    $query->groupBy(DB::raw('customer_id '));

    $records = $query->first();


    return isset($records->countTotal) ? $records->countTotal :0 ;
}

function totalProductsByStore($store_id) {

    $query = \App\Models\Product::with('store');
    $query->where('store_id', $store_id)->where('is_approved', 1)->where('deleted_at', NULL);
    $query->select(DB::raw('count(id) as countTotal'));
    $query->groupBy(DB::raw('store_id'));

    $records = $query->first();

    return isset($records->countTotal) ? $records->countTotal :0 ;
}

function totalActiveProductsByStore($store_id) {

    $query = \App\Models\Product::with('store');
    $query->where('store_id', $store_id)->where('status', true)->where('is_approved', true)->where('deleted_at', NULL);
    $query->select(DB::raw('count(id) as countTotal'));
    $query->groupBy(DB::raw('store_id'));

    $records = $query->first();

    return isset($records->countTotal) ? $records->countTotal :0 ;
}

function getVariationNames(Product $product)
{
    $attr_options = ProductAttribute::where('variation_id', $product->id)->withTrashed()->pluck('option_id')->toArray();
    return Attribute::whereIn('id', $attr_options)->pluck('title')->toArray();
}

function getLatestOrder() {
    $storeOrders = null;
    if(Auth()->guard('customer')->user() != null){
    $customer = Customer::findOrFail(Auth()->guard('customer')->user()->id);
    $orderService = new FilterStoreOrderService();

    $orderService->byCustomer($customer->id);
    $orderService->byArchive(StoreOrder::NOTARCHIVED);
    $storeOrders = $orderService->relations(['order.customer', 'order.currency', 'order.store_orders', 'order_items'])->latest()->get()->last();

    return $storeOrders;

    }
}

function makeSkuCode($store_id)
{

    $storeCode = '';
//    dd($store_id);
    $store = \App\Models\Store::where('id', $store_id)->first();
    if ($store) {
        $storeName = explode(" ", $store->name);

        if (count($storeName) == 1) {
            $storeCode = substr($store->name, 0, 3);
        }
        if (count($storeName) == 2) {
            $storeCode = substr($storeName[0], 0, 1) . substr($storeName[1], 0, 1) . substr($storeName[1], -1);
        }
        if (count($storeName) > 2) {
            $storeCode = substr($storeName[0], 0, 1) . substr($storeName[1], 0, 1) . substr($storeName[2], 0, 1);
        }
    }

    return $storeCode;
}

// get discount
function couponDiscount($couponCode = "") {

    $cartSessionId = UtilityHelpers::getCartSessionId();
    $cart = Cart::where('session_id', $cartSessionId)->first();
    $couponService = new CouponDiscountService();
    $discount = $couponService->applyCoupon($couponCode, $cart);

}

function getDefaultLocale()
{
    return app()->getLocale();
}

function getDefaultMetaTitle()
{
    $defaultLang = getDefaultLocale();
    $defaultMetaTitle = get_option('meta_title_'.$defaultLang);

    return $defaultMetaTitle != null ? $defaultMetaTitle : __(config('app.name'));
}

function getDefaultMetaDescription()
{
    $defaultLang = getDefaultLocale();
    $defaultMetaTitle = get_option('meta_description_'.$defaultLang);

    return $defaultMetaTitle != null ? $defaultMetaTitle : __(config('app.name'));
}

function getDefaultMetaKeywords()
{
    $defaultLang = getDefaultLocale();
    $defaultMetaTitle = get_option('meta_keywords_'.$defaultLang);

    return $defaultMetaTitle != null ? $defaultMetaTitle : __(config('app.name'));
}

function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
, $_SERVER["HTTP_USER_AGENT"]);
}

function getFormatedDate($datetime)
{
    $date = date("d-m-Y", strtotime($datetime));
    return $date;
}

function getFormatedDateTime($datetime)
{
    $dateTime = date("d-m-Y h:i A", strtotime($datetime));
    return $dateTime;
}

function getCurrentLang()
{
    $langCode = app()->getLocale();
    $lang = Language::where('code', $langCode)->first();

    return $lang;
}

function getAttributeWithOption(Product $product)
{
    $attr_options = ProductAttribute::where('variation_id', $product->id)->withTrashed()->pluck('option_id')->toArray();
//    $attributes = Attribute::whereIn('id', $attr_options)->pluck('title')->toArray();
    $attributes = Attribute::with('parent')->whereIn('id', $attr_options)->get();
    $attrArray = [];
    foreach ($attributes as $attr) {
        $attributeTitle = $attr->parent->attribute_translates != null ? $attr->parent->attribute_translates->title : $attr->parent->title;
        $optionTitle = $attr->attribute_translates != null ? $attr->attribute_translates->title : $attr->title;
        $attrArray[$attributeTitle] = $optionTitle;
    }
    return $attrArray;
}

function getAttrbiuteString($attributes)
{
    if (count($attributes) == 0) {
        return null;
    }

    $attrString = "";
    array_walk($attributes,
        function ($item, $key) use (&$attrString) {
            $attrString .= $key . ":" . $item . ", ";
        }
    );

    $attrString = substr($attrString, 0, -2);
    return $attrString;
}

/**
 * get customer from auth
 * @return \Illuminate\Contracts\Auth\Authenticatable|null
 */
function getCustomer()
{
    $customer = null;
    if (Auth::guard('customer')->check()) {
        $customer = Auth::guard('customer')->user();
    } else if (Auth('sanctum')->check()) {
        $customer = Auth('sanctum')->user();
    }

    return $customer;
}

function addCustomerGetResponse($name,$email) {
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
          CURLOPT_POSTFIELDS =>'{
          "name": "'.$name.'",
          "campaign": {
            "campaignId": "rReGu"
          },
          "email": "'.$email.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'X-Auth-Token: api-key lolibwrp4nrkrysoi9648b8aici67m2e',
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
//        echo $response;
}

