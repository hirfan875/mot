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
use App\Exceptions\InvalidDhlLogsException;

class DHLCurlService {

  
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

    public function callCurl($req_url, $req_xml)
    {
        
        $response='';
        $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $req_url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$req_xml,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/atom+xml'
              ),
            ));

        $response = curl_exec($curl);
           if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            throw new InvalidDhlLogsException($error_msg);
            }
        curl_close($curl);
        
        return $response;
    }

}
