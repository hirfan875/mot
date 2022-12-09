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
use App\Service\DhlRateXmlService;
use App\Models\DhlLog;

class DhlService {

    protected $logger;

    /**
     * OrderService constructor.
     */
    public function __construct() {
        $this->logger = getLogger('DHLService');
    }

    public function getShipmentRequest(array $request, StoreOrder $storeOrder)
    {        
        $results = $this->saveShipmentRequest($request, $storeOrder);
        
        $dhl_req_url = config('app.dhl_req_url').'expressRateBook';
                
        $seller_address = explode(PHP_EOL, $storeOrder->seller->address);
        $customer_address = explode(PHP_EOL, $storeOrder->order->address);
        date_default_timezone_set('Europe/Istanbul');
        $the_date = date("Y-m-d\TH:i:s", strtotime($request['shiptimestamp']." " .$request['ship_time'])).' GMT+01:00';       
        $ShipTimestamp = $the_date ? $the_date : date("Y-m-d\TH:i:s", strtotime('+1 week')).' GMT+01:00';
        
        $recipientCountryCode = $storeOrder->order->customerAddresses->countries->code;
        $serviceType = 'P';
        $content = 'NON_DOCUMENTS';
        if($recipientCountryCode == 'TR') {
            $serviceType = 'N';
            $content = 'DOCUMENTS';
        }
        
        $data = [
            'dhl_account_number' => config('app.dhl_account'),
            'dhl_username' => config('app.dhl_username'),
            'dhl_pwd' => config('app.dhl_pwd'),
            'messageTime' => '2021-08-15T11:28:56.000-08:00',
            'messageReference' => 'Test-Shipment_1_20210815-1951',
            'currency' => 'TRY',
            'ShipTimestamp' => $ShipTimestamp,
            'insuredValue' => isset($request['insured-value']) ? $request['insured-value'] : 0,
            'weight' => isset($request['weight']) ? $request['weight'] : '0.2',
            'length' => isset($request['length']) ? $request['length'] : '0',
            'width' => isset($request['width']) ? $request['width'] : '0',
            'height' => isset($request['height']) ? $request['height'] : '0',
            'customerReferences' => isset($request['customer-references']) ? $request['customer-references'] : 'TEST TR-KW',
            'sPersonName' => $storeOrder->seller->name,
            'sCompanyName' => $storeOrder->seller->name,
            'sPhoneNumber' => $storeOrder->seller->phone,
            'sEmailAddress' => isset($storeOrder->seller->staff[0]->email) ? $storeOrder->seller->staff[0]->email : '',
            'sStreetLines' => substr($storeOrder->seller->address, 0, 35),
            'sStreetName' => $storeOrder->seller->state ? '<StreetName>' . $storeOrder->seller->state . '</StreetName>' : '',
            'sCity' => $storeOrder->seller->city,
            'sPostalCode' => $storeOrder->seller->zipcode,
            'sCountryCode' => $storeOrder->seller->country->code,
            'rPersonName' => $storeOrder->order->customer->name,
            'rCompanyName' => 'MoT',
            'rPhoneNumber' => $storeOrder->order->customerAddresses->phone,
            'rEmailAddress' => $storeOrder->order->customer->email,
            'rStreetLines' => substr($storeOrder->order->customerAddresses->address, 0, 35),
            'rCity' => $storeOrder->order->customerAddresses->cities->title,
            'rPostalCode' => $storeOrder->order->customerAddresses->zipcode,
            'rCountryCode' => $storeOrder->order->customerAddresses->countries->code,
            'paymentInfo' => 'DAP',
            'numberOfPieces' => '1',
            'description' => 'test description',
            'customsValue' => '1',
            'invoiceDate' => '2021-03-17',
            'invoiceNumber' => '1',
            'placeOfIncoterm' => 'test',
            'commodityCode' => '55',
            'itemNumber' => '1',
            'quantity' => '1',
            'quantityUnitOfMeasurement' => 'PCS',
            'itemDescription' => 'Please do not simply type "sample" as it will require additional investigation at customs',
            'unitPrice' => number_format($storeOrder->delivery_fee, 3),
            'netWeight' => '0.2',
            'grossWeight' => '0.2',
            'manufacturingCountryCode' => 'TR',
            'Content' => $content,
            'ServiceType' => $serviceType,
        ];

        $shipmentxml = view('dhl.shipment-request', compact('data'))->render();
        
        
        $dhlCurlService = new DHLCurlService();
        $response = $dhlCurlService->callCurl($dhl_req_url, $shipmentxml);
        
        $data = ['type'=> 'ShipmentRequest'];
        $this->saveDHLLogs($data, $storeOrder->order, $shipmentxml, $response);       

        $response = <<<EOF
        $response    
        EOF;

        libxml_use_internal_errors(true);
        $parser = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        if (false === $parser) {
            echo "Failed loading XML\n";
            foreach (libxml_get_errors() as $error) {
                echo "\t", $error->message;
                throw new \ErrorException($error->message);
            }
        }
        
        $parserEnv = $parser->children('SOAP-ENV', true);
        
        $notification = $parserEnv->Body->children('shipresp', true)
                ->ShipmentResponse->children()
                ->Notification->children()->Message;
        
         if( (string) $notification != ''){
            throw new \ErrorException($notification);
        }
 
        $ServiceHeader = $parserEnv->Body->children('shipresp', true)
                ->ShipmentResponse->children()
                ->Response->children();
        
        foreach($ServiceHeader as $val) {
            $MessageTime = (string) $val->MessageTime;
            $MessageReference= (string) $val->MessageReference;
            $ServiceInvocationID = (string) $val->ServiceInvocationID;
        }
        
        $TrackingNumber = $parserEnv->Body->children('shipresp', true)
                ->ShipmentResponse->children()
                ->PackagesResult->children()->PackageResult->children()->TrackingNumber;

        $ShipmentIdentificationNumber_AWB = $parserEnv->Body->children('shipresp', true)
                ->ShipmentResponse->children()->ShipmentIdentificationNumber;

        $LabelImageFormat = $parserEnv->Body->children('shipresp', true)
                ->ShipmentResponse->children()->LabelImage->children()->LabelImageFormat;

        $GraphicImage = $parserEnv->Body->children('shipresp', true)
                ->ShipmentResponse->children()->LabelImage->children()->GraphicImage;

        header('Content-type: application/pdf');

        //Decode pdf content
        $pdf_decoded = base64_decode($GraphicImage);

//        echo $pdf_decoded;

        //Write data back to pdf file
        $file_name = 'dhl/'.$ShipmentIdentificationNumber_AWB . '.pdf';
        $pdf = fopen($file_name, 'w');
        fwrite($pdf, $pdf_decoded);
        fclose($pdf);
        
        $request = [
            'message_time' => $MessageTime,
            'message_reference' => $MessageReference,
            'service_invocation_id' => $ServiceInvocationID,
            'tracking_number' => $TrackingNumber,
            'label_image_format' => $LabelImageFormat,
            'graphic_image' => $GraphicImage,
            'shipment_identification_number_awb' => $ShipmentIdentificationNumber_AWB,
            'file' => $file_name,
        ];
        
        $results = $this->saveShipmentResponse($request, $storeOrder);
        
    }
    
    
    public function getTrackShipmentRequest($request, StoreOrder $storeOrder)
    {
        
        $dhl_req_url = config('app.dhl_req_url').'glDHLExpressTrack';
        
        $data = [
            'dhl_account_number' => config('app.dhl_account'),
            'dhl_username' => config('app.dhl_username'),
            'dhl_pwd' => config('app.dhl_pwd'),
            'awbNumber' => $request['number_awb'],
            'messageTime' => $request['message_time'],
            'messageReference' => $request['message_reference'],
            'levelOfDetails' => 'ALL_CHECKPOINTS',
            'piecesEnabled' => 'B',
        ];

        $trackingxml = view('dhl.tracking-request', compact('data'))->render();
        
        $dhlCurlService = new DHLCurlService();
        $response = $dhlCurlService->callCurl($dhl_req_url, $trackingxml);
        
        $data = ['type'=> 'trackingRequest'];
        $this->saveDHLLogs($data, $storeOrder->order, $trackingxml, $response);

        $response = <<<EOF
            $response        
            EOF;

        libxml_use_internal_errors(true);
        
        $parser = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        if (false === $parser) {
            echo "Failed loading XML\n";
            foreach (libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
        }

        $parserEnv = $parser->children('SOAP-ENV', true);
        $AWBInfo = $parserEnv->Body->children('ser-root', true)
                ->trackShipmentRequestResponse->children()
                ->trackingResponse->children('ns', true)
                ->TrackingResponse->children()
                ->AWBInfo->children();
        
        foreach($AWBInfo as $val) {
            $AWBNumber = (string) $val->AWBNumber;
            $Status = (string) $val->Status->ActionStatus;
            $ShipperName = (string) $val->ShipmentInfo->ShipperName;
            $ShipmentEvent = $val->ShipmentInfo->ShipmentEvent;
        }
        
        if($Status == "Failure") {
            
            $ConditionData = $AWBInfo->ArrayOfAWBInfoItem->children()->Status->children()->Condition->children()->ArrayOfConditionItem->children()->ConditionData;
            echo $ConditionData;
            return;
        }
        
        if(isset($ShipmentEvent->ArrayOfShipmentEventItem )){
            foreach($ShipmentEvent->ArrayOfShipmentEventItem as $evn){

                $request = [
                    'awb_number' => $AWBNumber,
                    'date' => $evn->Date,
                    'time' => $evn->Time,
                    'event_code' => $evn->ServiceEvent->EventCode,
                    'track_event_desc' => $evn->ServiceEvent->Description,
                    'area_code' => $evn->ServiceArea->ServiceAreaCode,
                    'area_desc' => $evn->ServiceArea->Description,
                ];

                $results = $this->saveTrackShipmentRequest($request, $storeOrder);

                $storeOrder->load(['order.customer']);
                $orderService = new OrderService;
                    $user = $storeOrder->order->customer;

                if($evn->ServiceEvent->EventCode == 'PU' && StoreOrder::READY_ID == $storeOrder->status){

                    $status=StoreOrder::SHIPPED_ID;

                    $orderStatus = $orderService->storeOrderStatus($storeOrder, $status, $user);
                }

                if($evn->ServiceEvent->EventCode == 'OK' && StoreOrder::SHIPPED_ID == $storeOrder->status){
                    $status=StoreOrder::DELIVERED_ID;

                   $orderStatus = $orderService->storeOrderStatus($storeOrder, $status, $user);
                }

            }
        }
        
    }
    
    public function getPickUpRequest($request, StoreOrder $storeOrder)
    {
        $dhl_req_url = config('app.dhl_req_url').'requestPickup';
          
        $seller_address = explode(PHP_EOL, $storeOrder->seller->address);
        $customer_address = explode(PHP_EOL, $storeOrder->order->address);
        
        date_default_timezone_set('Europe/Istanbul');
        $the_date = date("Y-m-d\TH:i:s", strtotime($request['pickup_date']." " .$request['pickup_time'])).' GMT+01:00';       
        $pickup_time = $the_date ? $the_date : date("Y-m-d\TH:i:s", strtotime('+1 week')).' GMT+01:00';

        $data = [
            'dhl_account_number' => config('app.dhl_account'),
            'dhl_username' => config('app.dhl_username'),
            'dhl_pwd' => config('app.dhl_pwd'),
            'message_id' => '12341234123444454587435111111',
            'location_close_time' => $request['location_close_time'] ? date("H:i",strtotime($request['location_close_time'])) : '17:00',
            'special_instruction' =>  '',
            'pickup_location' =>  '',
            'pickup_time' => $pickup_time,
            'weight' => $request['weight'] ? $request['weight'] : '0.2',
            'length' => $request['length'] ? $request['length'] : '0',
            'width' => $request['width'] ? $request['width'] : '0',
            'height' => $request['height'] ? $request['height'] : '0',
            'sPersonName' => $storeOrder->seller->name,
            'sCompanyName' => $storeOrder->seller->name,
            'sPhoneNumber' => $storeOrder->seller->phone,
            'sEmailAddress' => isset($storeOrder->seller->staff[0]->email) ? $storeOrder->seller->staff[0]->email : '',
            'sStreetLines' => substr($storeOrder->seller->address, 0, 35),
            'sCity' => $storeOrder->seller->city,
            'sPostalCode' => $storeOrder->seller->zipcode,
            'sCountryCode' => $storeOrder->seller->country->code,
            'rPersonName' => $storeOrder->order->customer->name,
            'rCompanyName' => 'MoT',
            'rPhoneNumber' => $storeOrder->order->customerAddresses->phone,
            'rEmailAddress' => $storeOrder->order->customer->email,
            'rStreetLines' => substr($storeOrder->order->customerAddresses->address, 0, 35),
            'rCity' => $storeOrder->order->customerAddresses->cities->title,
            'rPostalCode' => $storeOrder->order->customerAddresses->zipcode,
            'rCountryCode' => $storeOrder->order->customerAddresses->countries->code,
        ];

        $pickupxml = view('dhl.pickUp-request', compact('data'))->render();
        
        $dhlCurlService = new DHLCurlService();
        $response = $dhlCurlService->callCurl($dhl_req_url, $pickupxml);
              
            $data = ['type'=> 'PickUpShipment'];
            $this->saveDHLLogs($data, $storeOrder->order, $pickupxml, $response);

            $response = <<<EOF
            $response        
            EOF;
        
            libxml_use_internal_errors(true);

            $parser = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
            if (false === $parser) {
                echo "Failed loading XML\n";
                foreach (libxml_get_errors() as $error) {
                    echo "\t", $error->message;
                }
            }
            
            $parserEnv = $parser->children('SOAP-ENV', true);
            $pickUpResponse = $parserEnv->Body->children('pickupresp', true)
                    ->PickUpResponse->children();
   
            if((string) $pickUpResponse->Notification->Message != ''){
                throw new \ErrorException($pickUpResponse->Notification->Message);
            }

            if(isset($pickUpResponse->DispatchConfirmationNumber)){
                $request = [
                    'dispatch_confirmation' => $pickUpResponse->DispatchConfirmationNumber,
                ];
                
                $results = $this->savePickUpResponse($request, $storeOrder);
               
            }
        
    }
    
    public function getRateRequestByStore($storeId, $products, $address, $cart, $order)
    {
        
        $dhl_account_number = config('app.dhl_account'); 
        $dhl_req_url = config('app.dhl_req_url').'expressRateBook';
        $dhl_username = config('app.dhl_username'); 
        $dhl_pwd = config('app.dhl_pwd'); 
        
        $stores = Store::where('id', $storeId)->first();
        $DhlRateXmlService = new DhlRateXmlService();
        $ratexml = $DhlRateXmlService->getRateXml($stores, $products, $address);
        
        $dhlCurlService = new DHLCurlService();
        $response = $dhlCurlService->callCurl($dhl_req_url, $ratexml);
        
        $data = ['type'=> 'RateRequest'];
        $this->saveDHLLogs($data, $order, $ratexml, $response, $cart);

            $response = <<<EOF
            $response        
            EOF;
        
            libxml_use_internal_errors(true);
        
            $parser = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
            if (false === $parser) {
                echo "Failed loading XML\n";
                foreach (libxml_get_errors() as $error) {
                    echo "\t", $error->message;
                }
            }

            $parserEnv = $parser->children('SOAP-ENV', true);
            $rateResponse = $parserEnv->Body->children('rateresp', true)
                    ->RateResponse->children()->Provider->children();
       
            if((string) $rateResponse->Notification->Message != ''){
                throw new \ErrorException($rateResponse->Notification->Message);
            }
        
            foreach($rateResponse->Service as $r){
                if ($r['type']=='P' || $r['type']=='N'){
                    $TotalNet= $r->TotalNet;
                }
            }

        return $TotalNet; 
    }
    
    public function getRateRequestForValidateStoreAddress($storeOrder)
    {
        
        $dhl_req_url = config('app.dhl_req_url').'expressRateBook';
        $pickup_time = Carbon::now()->timezone('Europe/Istanbul')->nextWeekday()->format("Y-m-d\T10:00:00").' GMT-06:00';

        $stores = Store::where('id', $storeOrder)->first();
        
        $rCity = 'Hawalli';
        $rCountryCode = 'KW';
        $rPostalCode = '';
        $rAddress = 'No:26-A Shah waliullah';

        $data = [
            'dhl_account_number' => config('app.dhl_account'),
            'dhl_username' => config('app.dhl_username'),
            'dhl_pwd' => config('app.dhl_pwd'),
            'rCity' => $rCity,
            'rCountryCode' => $rCountryCode,
            'rPostalCode' => $rPostalCode,
            'rAddress' => $rAddress,
            'sStreetLines' => substr($stores->address, 0, 35),
            'sCity' => $stores->city,
            'sPostalCode' => $stores->zipcode,
            'sCountryCode' => $stores->country->code,
            'pickup_time' => $pickup_time,
            'products' => '',
            'DropOffType'=> 'REQUEST_COURIER',
            'UnitOfMeasurement'=> 'SU',
            'Content'=> 'NON_DOCUMENTS',
            'DeclaredValue'=> '0.2',
            'DeclaredValueCurrecyCode'=> 'TRY',
            'PaymentInfo'=> 'DAP',
        ];
        
        $rate_xml='';
        $rate_xml = view('dhl.valid-rate-request', compact('data'))->render();
        
               
        $dhlCurlService = new DHLCurlService();
        $response = $dhlCurlService->callCurl($dhl_req_url, $rate_xml);
        
//        $data = ['type'=> 'RateRequest'];
//        $this->saveDHLLogs($data, $order, $ratexml, $response, $cart);

            $response = <<<EOF
            $response        
            EOF;
        
            libxml_use_internal_errors(true);
        
            $parser = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
            if (false === $parser) {
                echo "Failed loading XML\n";
                foreach (libxml_get_errors() as $error) {
                    echo "\t", $error->message;
                }
            }

            $parserEnv = $parser->children('SOAP-ENV', true);
            $rateResponse = $parserEnv->Body->children('rateresp', true)
                    ->RateResponse->children()->Provider->children();
       
            if((string) $rateResponse->Notification->Message != ''){
                throw new \ErrorException($rateResponse->Notification->Message);
            }
        
            foreach($rateResponse->Service as $r){
                if ($r['type']=='P' || $r['type']=='N'){
                    $TotalNet = (string) $r->TotalNet;
                }
            }

        return $TotalNet; 
    }
    
    /**
     * save Shipment Request data from request
     *
     * @param array $request
     * @param StoreOrder $storeOrder
     */
    private function saveDHLLogs(array $request, $order, $xml_request, $xml_response, $cart = '') 
    {
        
        $saveDHLLogs = new DhlLog();
        $saveDHLLogs->order_id = isset($order->id) ? $order->id : '';
        $saveDHLLogs->type = $request['type'];
        $saveDHLLogs->request = $xml_request;
        $saveDHLLogs->response = $xml_response;
        $saveDHLLogs->save();
    }
    
    /**
     * save Shipment Request data from request
     *
     * @param array $request
     * @param StoreOrder $storeOrder
     * @param ShipmentResponse $shipmentRequest
     */
    private function saveShipmentRequest(array $request, StoreOrder $storeOrder) 
    {
        $shipmentRequest = ShipmentRequest::firstOrNew(['store_order_id' => $storeOrder->id ]);
        $shipmentRequest->store_order_id = $storeOrder->id;
        $shipmentRequest->insured_value = isset($request['insured-value']) ? $request['insured-value'] : 0;
        $shipmentRequest->weight = isset($request['weight']) ? $request['weight'] : '0.2';
        $shipmentRequest->length = isset($request['length']) ? $request['length'] : 0;
        $shipmentRequest->width = isset($request['width']) ? $request['width'] : 0;
        $shipmentRequest->height = isset($request['height']) ? $request['height'] : 0;
        $shipmentRequest->shiptimestamp = isset($request['shiptimestamp']) ? $request['shiptimestamp']: date('Y-m-d', strtotime('+1 week'));
        $shipmentRequest->customer_references = isset($request['customer-references']) ?  $request['customer-references'] : 0;
        $shipmentRequest->save();
    }
    
    /**
     * save Shipment Request data from request
     *
     * @param array $request
     * @param StoreOrder $storeOrder
     * @param ShipmentResponse $shipmentResponse
     */
    private function saveShipmentResponse(array $request, StoreOrder $storeOrder) 
    {
        $shipmentResponse = ShipmentResponse::firstOrNew(['order_id' => $storeOrder->order_id, 'store_order_id' => $storeOrder->id ]);
        $shipmentResponse->order_id = $storeOrder->order_id;
        $shipmentResponse->store_order_id = $storeOrder->id;
        $shipmentResponse->message_time = $request['message_time'];
        $shipmentResponse->message_reference = $request['message_reference'];
        $shipmentResponse->service_invocation_id = $request['service_invocation_id'];
        $shipmentResponse->tracking_number = $request['tracking_number'];
        $shipmentResponse->label_image_format = $request['label_image_format'];
        $shipmentResponse->graphic_image = $request['graphic_image'];
        $shipmentResponse->shipment_identification_number_awb = $request['shipment_identification_number_awb'];
        $shipmentResponse->file = $request['file'];
        $shipmentResponse->save();
    }
    
    /**
     * save Shipment Request data from request
     *
     * @param array $request
     * @param StoreOrder $storeOrder
     * @param ShipmentResponse $shipmentRequest
     */
    private function saveTrackShipmentRequest(array $request, StoreOrder $storeOrder) 
    {
        $trackShipmentResponse = TrackShipmentResponse::firstOrNew([ 'store_order_id' => $storeOrder->id,'event_code' => $request['event_code'] ]);
        $trackShipmentResponse->store_order_id = $storeOrder->id;
        $trackShipmentResponse->awb_number = $request['awb_number'];
        $trackShipmentResponse->date = $request['date'];
        $trackShipmentResponse->time = $request['time'];
        $trackShipmentResponse->event_code = $request['event_code'];
        $trackShipmentResponse->track_event_desc = $request['track_event_desc'];
        $trackShipmentResponse->area_code = $request['area_code'];
        $trackShipmentResponse->area_desc = $request['area_desc'];
        $trackShipmentResponse->save();
    }
    
    /**
     * save Shipment Request data from request
     *
     * @param array $request
     * @param StoreOrder $storeOrder
     */
    private function savePickUpResponse(array $request, StoreOrder $storeOrder) 
    {
        $trackShipmentResponse = PickUpResponse::firstOrNew([ 'store_order_id' => $storeOrder->id,'dispatch_confirmation' => $request['dispatch_confirmation'] ]);
        $trackShipmentResponse->store_order_id = $storeOrder->id;
        $trackShipmentResponse->dispatch_confirmation = $request['dispatch_confirmation'];
        $trackShipmentResponse->save();
    }

}
