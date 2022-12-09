<?php

namespace App\Service\PaymentMethods;

use App\Models\Order;
use App\Models\StoreOrder;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Models\TransactionAttempt;
use App\Models\OrderTransaction;
use App\Service\TransactionService;
use App\Models\MyFatoorahTransactions;
use Monolog\Logger;
use App\Models\ReturnOrderItems;
use App\Models\OrderRefund;
use DB;
use App\Helpers\UtilityHelpers;

class MyFatoorah
{
    protected $callBackUrl;
    protected $sendPaymentUrl;
    protected $verifyPaymentUrl;
    protected $errorUrl;
    protected $token;
    protected $access_token;
    protected $refundPaymentUrl;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->logger = getLogger('my-fatoorah-payment', Logger::DEBUG, 'logs/my-fatoorah-payment.log');
        $this->token = config('payment.my-fatoorah.token');
        $this->token = 'Bearer ' . $this->token;
        $this->access_token = $this->token;
        $this->sendPaymentUrl = config('payment.my-fatoorah.send-payment-url');
        $this->verifyPaymentUrl = config('payment.my-fatoorah.verify-payment-url');
        $this->refundPaymentUrl = config('payment.my-fatoorah.refund-payment-url');
    }

    /**
     * process payment
     *
     * @param int $order_id
     * @return array
     */
    public function processPayment(int $order_id): array
    {
        $order = Order::with(['customer', 'order_items.product'])->findOrFail($order_id);
        $this->logger->debug('my fatoorah request for order : ', [ $order ]);
        
        if($order->status == Order::UNIITIATED_ID){
            $order->toConfirm();
        }
        
        $currency = getCurrency();
        $transactionService = new TransactionService();
        $transaction = $transactionService->createAttempt($order->id);
        $this->logger->debug('create transcation : ', [ $transaction ]);
        $post_data = $this->paymentData($order, $transaction->id, $currency);
        $this->logger->debug('my fatoorah post data : ', [ $post_data ]);
        
        $response = Http::withHeaders([
            'Authorization' => $this->access_token
        ])->withOptions(['verify' => false])->post($this->sendPaymentUrl, $post_data);
        
        $this->logger->debug('my fatoorah response : ', [ $response ]);

        $transaction->transaction_request = json_encode($post_data);
        $transaction->transaction_response = $response->json();
        $transaction->save();
        $this->logger->debug('create transcation : ', [ $transaction ]);

        if ($response->failed()) {
             $this->logger->critical('MyFatoorah Error on Checkout Form: ', [ $response ]);
            throw new \Exception(__($response['Message']));
        }

        return $this->getSuccessResponse($response);
    }

    /**
     * @param $keyId
     * @param $keyType
     * @param $transactionAttemptId
     * @return array
     */
    public function verifyPayment($keyId, $keyType, $transactionAttemptId): array
    {
        $transactionAttempt = TransactionAttempt::find($transactionAttemptId);
        $this->logger->debug('my fatoorah transactionAttempt : ', [ $transactionAttempt ]);
        $post_data = [
            'Key' => $keyId,
            'KeyType' => $keyType
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->access_token
        ])->withOptions(['verify' => false])->post($this->verifyPaymentUrl, $post_data);
        
        $this->logger->debug('my fatoorah verify : ', [ $response ]);

        $this->createTransactionAttempt($post_data, $response, $transactionAttempt->order->id, $transactionAttempt->order);
       
        if ($response->failed() || !$response['IsSuccess']) {
             $this->logger->debug('my fatoorah getErrorResponse : ', [ $this->getErrorResponse($response) ]);
            return $this->getErrorResponse($response);
        }
        $this->logger->debug('my fatoorah response : ', [ $response ]);
        return [
            'success' => true,
            'data' => $response['Data']
        ];
    }

    public function getMyFatoorahPayment($order): array
    {
        $post_data = [
            'Key' => $order->invoice_id,
            'KeyType' => Order::INVOICEID
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->access_token
        ])->withOptions(['verify' => false])->post($this->verifyPaymentUrl, $post_data);

        $this->createTransactionAttempt($post_data, $response, $order->id, $order);

        if ($response->failed() || !$response['IsSuccess']) {
            return $this->getErrorResponse($response);
        }

        return [
            'success' => true,
            'data' => $response->json()['Data']
        ];
    }

    /**
     * set payment data
     *
     * @param Order $order
     * @param int $transaction_id
     * @return array
     */
    private function paymentData(Order $order, int $transaction_id, $currency ): array
    {
        $currencyCode = $currency->code;
        
        if($currency->code == 'TRY' || $currency->code == 'EUR'){
            $currencyCode = 'KWD';
        }
        
        $unstock = [];
        foreach ($order->order_items as $item) {
            if ($item->product->stock < $item->quantity) {
                $unstock[] = $item->product->title;
            }
        }
        
        $productsString = implode(", </br>", $unstock);
        if (count($unstock) > 0) {
            throw new \Exception(__('Not enough stock for ') . $productsString);
        }

        foreach ($order->order_items as $item) {
            $title = $item->product->title;
            if($item->product->isVariation()){
                $attributeNames = UtilityHelpers::getVariationNames($item->product);
                $title = $item->product->parent->title. ' '. implode(', ', $attributeNames);
            }

//            $item->product->update(['stock' => DB::raw("`stock` - " . $item->quantity)]);
//            $item->product->refresh();
        }
        
        return [
            'NotificationOption' => 'LNK',
            'CustomerName' => $order->customer->name,
            'DisplayCurrencyIso' => $currencyCode,
//            'MobileCountryCode' => '+965',
//            'CustomerMobile' => '9555387570',
            'CustomerEmail' => $order->customer->email,
            'InvoiceValue' => number_format(currencyInKWD( $currencyCode , $order->total), 3, '.', ''),
            'CallBackUrl' => route('myfatoorah-callback', [$transaction_id]),
            'ErrorUrl' => route('myfatoorah-callback', [$transaction_id]),
            'Language' => 'EN',
            'CustomerReference' => $transaction_id,
            'CustomerAddress' => $this->customerAddress($order),
            'InvoiceItems' => ''
        ];
        
        // 'InvoiceItems' => $this->invoiceItems($order,$currencyCode)
    }

    /**
     * set payment customer address
     *
     * @param Order $order
     * @return array
     */
    private function customerAddress(Order $order): array
    {
        return [
            'Address' => $order->address
        ];
    }

    /**
     * set invoice items
     *
     * @param Order $order
     * @return array
     */
    private function invoiceItems(Order $order, $currencyCode): array
    {
        $items = [];
        $unstock = [];
        foreach ($order->order_items as $item) {
            if ($item->product->stock < $item->quantity) {
                $unstock[] = $item->product->title;
            }
        }
        
        $productsString = implode(", </br>", $unstock);
        if (count($unstock) > 0) {
            throw new \Exception(__('Not enough stock for ') . $productsString);
        }

        foreach ($order->order_items as $item) {
            
            $title = $item->product->title;
            if($item->product->isVariation()){
                $attributeNames = UtilityHelpers::getVariationNames($item->product);
                $title = $item->product->parent->title. ' '. implode(', ', $attributeNames);
            }
//
//            $item->product->update(['stock' => DB::raw("`stock` - " . $item->quantity)]);
//            $item->product->refresh();

            $items[] = [
                'ItemName' => $title,
                'Quantity' => $item->quantity,
                'UnitPrice' => number_format(currencyInKWD($currencyCode, $item->unit_price),  3, '.', '')
            ];
        }

        if (!empty($order->delivery_fee)) {
            $items[] = [
                'ItemName' => 'Delivery Fee',
                'Quantity' => 1,
                'UnitPrice' => number_format(currencyInKWD($currencyCode,$order->delivery_fee),  3, '.', '')
            ];
        }

        if (!empty($order->tax)) {
            $items[] = [
                'ItemName' => 'Tax',
                'Quantity' => 1,
                'UnitPrice' => number_format(currencyInKWD($currencyCode ,$order->tax),  3, '.', '')
            ];
        }

        return $items;
    }

    /**
     * get payment success response
     *
     * @param Response $response
     * @return array
     */
    private function getSuccessResponse(Response $response): array
    {
        return [
            'success' => true,
            'paymentUrl' => $response['Data']['InvoiceURL'],
            'invoiceId' => $response['Data']['InvoiceId']
        ];
    }

    /**
     * get error response
     *
     * @param Response $response
     * @return array
     */
    private function getErrorResponse(Response $response): array
    {
        return [
            'success' => false,
            'message' => $response['Message'],
            'errors' => $response['ValidationErrors'],
            'rawResult' => $response,
        ];
    }

    /**
     * @param $transactionAttemptId
     * @param $paymentResponse
     * @return Order
     */
    public function orderSuccess($transactionAttemptId, $paymentResponse) :Order
    {
        $transactionAttempt = TransactionAttempt::find($transactionAttemptId);
        $transactionAttempt->order->payment_id = $paymentResponse['data']['InvoiceTransactions'][0]['PaymentId'];
        $transactionAttempt->order->invoice_id = $paymentResponse['data']['InvoiceId'];
        $transactionAttempt->order->order_date = Carbon::now()->timestamp;
        $transactionAttempt->order->toPaid();
        $transactionAttempt->order->save();

        $this->createTransactionLogs($transactionAttempt, $paymentResponse);

        return $transactionAttempt->order;
    }

    /**
     * @param $transactionAttempt
     * @param $paymentResponse
     */
    private function createTransactionLogs($transactionAttempt, $paymentResponse)
    {
        $transaction = new MyFatoorahTransactions();
        foreach ($paymentResponse['data']['InvoiceTransactions'] as $transac) {
            /*invoice details*/
            $transaction->transaction_attempt_id = $transactionAttempt->id;
            $transaction->invoiceId = $paymentResponse['data']['InvoiceId'];
            $transaction->invoiceStatus = $paymentResponse['data']['InvoiceStatus'];
            $transaction->invoiceRefrence = $paymentResponse['data']['InvoiceReference'];
            $transaction->invoiceValue = $paymentResponse['data']['InvoiceValue'];
            $transaction->comments = $paymentResponse['data']['Comments'];
            $transaction->invoiceDisplayValue = $paymentResponse['data']['InvoiceDisplayValue'];

            /* transaction details */
            $transaction->paymentGateway = $transac['PaymentGateway'];
            $transaction->referenceId = $transac['ReferenceId'];
            $transaction->trackId = $transac['TrackId'];
            $transaction->transactionId = $transac['TransactionId'];
            $transaction->paymentId = $transac['PaymentId'];
            $transaction->authorizationId = $transac['AuthorizationId'];
            $transaction->transationValue = $transac['TransationValue'];
            $transaction->customerServiceCharge = $transac['CustomerServiceCharge'];
            $transaction->dueValue = $transac['DueValue'];
            $transaction->paidCurrency = $transac['PaidCurrency'];
            $transaction->paidCurrencyValue = $transac['PaidCurrencyValue'];
            $transaction->transactionStatus = $transac['TransactionStatus'];
            $transaction->currency = $transac['Currency'];
            $transaction->error = $transac['Error'];
            $transaction->errorCode = $transac['ErrorCode'];
            $transaction->cardNumber = $transac['CardNumber'];
            $transaction->save();
        }
    }

    /**
     * @param Order $order
     * @param null $ip
     * @return array
     * @throws \Exception
     */
    public function cancelPayment(Order $order, $ip = null)
    {
        $transaction = MyFatoorahTransactions::where(['invoiceId' => $order->invoice_id, 'invoiceStatus' => 'Paid'])->first();
        $currency = $order->currency;
        $currencyCode = $currency->code;
        
        if($currency->code == 'TRY' || $currency->code == 'EUR'){
            $currencyCode = 'KWD';
        }
        $refundPrice = number_format(currencyInKWD( $currencyCode , $transaction->invoiceValue), 3, '.', '');

        $post_data = [
            'KeyType' => Order::INVOICEID,
            'Key' => $order->invoice_id,
            'Amount' => $refundPrice,
            'Comment' => 'Refund Entire order',
            //Fill optional Data
            //"RefundChargeOnCustomer"  => false,
            //"ServiceChargeOnCustomer" => false,
            //"AmountDeductedFromSupplier"=> 0

        ];
        $cancelled = $this->RefundAPICall($post_data);
        $this->createTransactionAttempt($post_data, $cancelled['rawResult'], $order->id, $order);
        if($cancelled['success']) {
            $this->saveRefundRecord($cancelled['data'], $order, OrderRefund::CANCEL_ENTIRE_ORDER);
            return $cancelled;
        }
        throw new \Exception($cancelled['message']);
    }

    /**
     * @param $post_data
     * @return array
     */
    private function RefundAPICall($post_data)
    {
        $response = Http::withHeaders([
            'Authorization' => $this->access_token
        ])->withOptions(['verify' => false])->post($this->refundPaymentUrl, $post_data);
        if ($response->failed()) {
            return $this->getErrorResponse($response);
        }

        return [
            'success' => true,
            'data' => $response['Data'],
            'rawResult' => $response,
        ];
    }

    /**
     * @param $request
     * @param $response
     * @param null $order_id
     * @param null $morph
     * @return TransactionAttempt
     */
    private function createTransactionAttempt($request, $response, $order_id = null, $morph = null)
    {
        $transactionAttempt = new TransactionAttempt();
        $transactionAttempt->transaction_request = json_encode($request);
        $transactionAttempt->transaction_response = $response->body();
        $transactionAttempt->order_id = $order_id != null ? $order_id : null;
        $transactionAttempt->type = $morph != null ? get_class($morph) : null;
        $transactionAttempt->type_id = $morph != null ? $morph->id : null;
        $transactionAttempt->save();

        return $transactionAttempt;
    }

    /**
     * This method is calling when we cancel the entire order of specific store
     * @param StoreOrder $storeOrder
     * @param string|null $ip
     * @return StoreOrder
     */
    public function refundAndCancelPayment(StoreOrder $storeOrder, string $ip = null)
    {
        $this->logger->debug('Refunding cancelled order ');
        try {
            $refundPrice    = $storeOrder->sub_total + $storeOrder->delivery_fee;
            $currency = $storeOrder->order->currency;
            $currencyCode = $currency->code;

            if($currency->code == 'TRY' || $currency->code == 'EUR'){
                $currencyCode = 'KWD';
            }
            $refundPrice = number_format(currencyInKWD( $currencyCode , $refundPrice), 3, '.', '');
            $post_data = [
                'KeyType' => Order::INVOICEID,
                'Key' => $storeOrder->order->invoice_id,
                'Amount' => $refundPrice,
                'Comment' => 'Refund entire order of specific store',

            ];
            $refunded = $this->RefundAPICall($post_data);
            /* Transaction Attempt */
            $this->createTransactionAttempt($post_data, $refunded['rawResult'], $storeOrder->order->id, $storeOrder);

            if($refunded['success']) {
                $refundedOrder = $this->saveRefundRecord($refunded['data'], $storeOrder, OrderRefund::CANCEL_STORE_ORDER);
                return $storeOrder;
            }

            $this->logger->debug('Failed refund request ', [ $refunded['message'] ]);
            throw new \Exception($refunded['message']);

        } catch (\Exception $exception) {
            $this->logger->debug('Failed refund request ', [$exception]);
        }
    }

    /**
     * @param $refundedData
     * @param $morph
     * @param null $refund_type
     * @return OrderRefund
     */
    private function saveRefundRecord($refundedData, $morph, $refund_type = null)
    {
        $refund = new OrderRefund();
        $refund->status                 = 1;
        $refund->refund_amount          = $refundedData['Amount'];
        $refund->refund_type            = $refund_type;
        $refund->type                   = $morph != null ? get_class($morph) : null;
        $refund->type_id                = $morph != null ? $morph->id : null;
        $refund->notes                  = isset($refundedData['Comment']) ? $refundedData['Comment'] : null;
        $refund->refunded_id            = isset($refundedData['RefundId']) ? $refundedData['RefundId'] : null;
        $refund->refunded_refrence      = isset($refundedData['RefundReference']) ? $refundedData['RefundReference'] : null;
        $refund->save();

        return $refund;
    }

    /**
     * @param ReturnOrderItems $return_order_item
     * @return mixed
     */
    public function getRefundedOrderByReturnOrderItem(ReturnOrderItems $return_order_item)
    {
        return OrderRefund::where('return_order_item_id', $return_order_item->id)->first();
    }

    /**
     * this method is using when order is refunding against refund request
     * @param ReturnOrderItems $return_order_item
     * @return array
     * @throws \Exception
     */
    public function createAndSendRefundRequest(ReturnOrderItems $return_order_item)
    {
        $storeOrder = $return_order_item->order_item->store_order;
        $this->logger->debug('Creating refund request ', []);

        $refundPrice = ($return_order_item->order_item->unit_price + $return_order_item->order_item->delivery_fee) * $return_order_item->quantity;
        
        $currency = $storeOrder->order->currency;
        $currencyCode = $currency->code;

        if($currency->code == 'TRY' || $currency->code == 'EUR'){
            $currencyCode = 'KWD';
        }
        $refundPrice = number_format(currencyInKWD( $currencyCode , $refundPrice), 3, '.', '');
        $post_data = [
            'KeyType' => Order::INVOICEID,
            'Key' => $storeOrder->order->invoice_id,
            'Amount' => $refundPrice,
            'Comment' => $return_order_item->reason,
        ];
        $refunded = $this->RefundAPICall($post_data);
        /* Transaction Attempt */
        $this->createTransactionAttempt($post_data, $refunded['rawResult'], $storeOrder->order->id, $storeOrder);
        
        $this->logger->debug('Sending refund request ', [$post_data]);
        
        if(!$refunded['success']) {
            $this->logger->debug('Failed refund request ', [$refunded['message']]);
            if(isset($refunded['errors'][0]['Error'])){
                $refunded['message'] = $refunded['errors'][0]['Error'];
            }
            throw new \ErrorException($refunded['message']);
        }
        $this->logger->debug('Amount refunded successfully ', [$refunded['rawResult']->body()]);
        //saving data to refund record table
        $this->logger->debug('Saving data to order refund table ', [$refunded['rawResult']->body()]);
        $refundedOrder = $this->saveRefundRecord($refunded['data'], $return_order_item, OrderRefund::REFUND_REQUEST);
        $this->logger->debug('Saved data to order refund table ', [$refundedOrder]);

        return $refunded;
    }
}
