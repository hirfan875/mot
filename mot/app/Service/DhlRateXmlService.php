<?php

namespace App\Service;

use App\Events\OrderDelivered;
use App\Events\OrderStatusChange;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Store;
use App\Models\StoreOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Monolog\Logger;
use phpDocumentor\Reflection\Types\Collection;
use App\Service\CountryService;
use Illuminate\Support\Facades\Mail;
use App\Models\ShipmentResponse;
use App\Models\TrackShipmentResponse;
use App\Models\PickUpResponse;
use App\Models\ShipmentRequest;
use Illuminate\Http\Request;
use App\Service\MoTCartService;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Service\CouponDiscountService;
use App\Helpers\UtilityHelpers;
use App\Service\CustomerAddressService;
use Illuminate\Support\Facades\View;

class DhlRateXmlService {

  
    /** @var XML */
    protected $rate_xml;
    /** @var Logger */
    protected $logger;

    /**
     * OrderService constructor.
     */
    public function __construct() {
        $this->logger = getLogger('DhlRateXmlService');
    }

    public function getRateXml(Store $stores, $products, $address)
    {
        $pickup_time = Carbon::now()->timezone('Europe/Istanbul')->nextWeekday()->format("Y-m-d\T10:00:00").' GMT-06:00';

        $TotalNet = '';
        $data = array();
        $zipcode = '';
        if($address->countries->code == 'TR'){
            $zipcode = $address->zipcode ? $address->zipcode :'';
        }
                
        $data = [
            'dhl_account_number' => config('app.dhl_account'),
            'dhl_username' => config('app.dhl_username'),
            'dhl_pwd' => config('app.dhl_pwd'),
            'rCity' => isset($address->cities->title) ? $address->cities->title : $address->city,
            'rCountryCode' => $address->countries->code,
            'rPostalCode' => $zipcode,
            'rAddress' => substr($address->address, 0, 35),
            'sStreetLines' => substr($stores->address, 0, 35),
            'sCity' => $stores->city,
            'sPostalCode' => $stores->zipcode,
            'sCountryCode' => $stores->country->code,
            'pickup_time' => $pickup_time,
            'products' => $products,
            'DropOffType'=> 'REQUEST_COURIER',
            'UnitOfMeasurement'=> 'SU',
            'Content'=> 'NON_DOCUMENTS',
            'DeclaredValue'=> '0.2',
            'DeclaredValueCurrecyCode'=> 'TRY',
            'PaymentInfo'=> 'DAP',
        ];
        
        $rate_xml='';
        $rate_xml = view('dhl.rate-request', compact('data'))->render();
        return $rate_xml;
    }

}
