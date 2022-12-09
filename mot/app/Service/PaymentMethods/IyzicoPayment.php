<?php

namespace App\Service\PaymentMethods;

use App\Models\Cart;
use App\Models\Category;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItemTransaction;
use App\Models\OrderTransaction;
use App\Models\StoreOrder;
use App\Models\Store;
use App\Models\TransactionAttempt;
use App\Models\OrderItem;
use FontLib\TrueType\Collection;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\Cancel;
use Iyzipay\Model\CheckoutForm;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\Refund;
use Iyzipay\Request\CreateCancelRequest;
use Iyzipay\Request\CreateRefundRequest;
use Iyzipay\Request\RetrieveCheckoutFormRequest;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Options;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Locale;
use Monolog\Logger;
use Iyzipay\Request\CreateSubMerchantRequest;
use Iyzipay\Model\SubMerchantType;
use Iyzipay\Model\SubMerchant;
use Carbon\Carbon;
use App\Models\ReturnOrderItems;
use App\Models\OrderRefund;
use Iyzipay\Request\CreateApprovalRequest;
use Iyzipay\Model\Approval;

class IyzicoPayment
{
    protected $pos_id;
    protected $client_id;
    protected $client_secret;
    protected $authorizeUrl;
    protected $sendPaymentUrl;
    protected $notifyUrl;
    protected $errorUrl;

    protected $options;
    /** @var Logger */
    protected $logger;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->options = new Options();
        $this->options->setApiKey(config('iyzico.client_id'));
        $this->options->setSecretKey(config('iyzico.client_secret'));
        $this->options->setBaseUrl(config('iyzico.api_url'));
        $this->logger = getLogger('iyzico-payment', Logger::DEBUG, 'logs/iyzico-payment.log');
        $this->logger->debug('Iyzico options values', [$this->options]);
    }

    /**
     * authorize user
     *
     * @return array
     */
    public function authorize()
    {
    }

    /**
     * process payment
     *
     * @param Order $order
     * @param Cart $cart
     * @param string $ip
     * @return string
     * @throws \Exception
     */
    public function processPayment(Order $order, Cart $cart, string $ip, $address)
    {
        // dd($order);
        $transactionAttempt = new TransactionAttempt();
        $transactionAttempt->order_id = $order->id;
        $transactionAttempt->save();

        // create request class
        $request = new CreateCheckoutFormInitializeRequest();
        $request->setLocale(Locale::EN);
        $request->setConversationId($transactionAttempt->getConversationId());
        $request->setPrice($order->total);
        $request->setPaidPrice($order->total);
        $this->logger->debug('Order Price ', [$order->total]);
        $request->setCurrency(Currency::TL);
        $request->setBasketId("B1213" . $cart->id);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('iyzico-callback', [$transactionAttempt->id]));
        $request->setEnabledInstallments(array(2, 3, 6, 9));

        $request->setBuyer($this->getBuyer($order, $ip));
        $request->setShippingAddress($this->getShippingAddress($order));
        $request->setBillingAddress($this->getShippingAddress($order));
        $request->setBasketItems($this->getBasketItems($order));
        $address->phone;

        // make request
        /** @var CheckoutFormInitialize $checkoutFormInitialize */
        $checkoutFormInitialize = CheckoutFormInitialize::create($request, $this->options);
        $transactionAttempt->transaction_request = $request->toJsonString();
        $transactionAttempt->transaction_response = $checkoutFormInitialize->getRawResult();
        $transactionAttempt->type = 'App\Models\Order';
        $transactionAttempt->type_id = $order->id;
        $transactionAttempt->iyzico_type = 'Iyzipay\Model\CheckoutFormInitialize::create';
        $transactionAttempt->save();

        if ("success" !== strtolower($checkoutFormInitialize->getStatus())) {
            $this->logger->debug($checkoutFormInitialize->getErrorMessage(), []);
            throw new \Exception($checkoutFormInitialize->getErrorMessage());
        }
        $this->logger->debug('Order Payment Success', $order->toArray());
        /** @var string $checkoutForm */
        $checkoutForm = $checkoutFormInitialize->getCheckoutFormContent();

        $this->logger->debug('Sending to Confirm Order', $order->toArray());
        $order->toConfirm();
        return $checkoutForm;
    }

    /**
     * @param TransactionAttempt $transactionAttempt
     * @param $token
     * @return mixed
     */
    public function retrievePayment(TransactionAttempt $transactionAttempt, $token)
    {
        $request = new RetrieveCheckoutFormRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($transactionAttempt->getConversationId());
        $request->setToken($token);

        /** @var CheckoutForm $checkoutForm */
        $this->logger->debug('Getting IyZiCo Payment Status', []);
        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $this->options);
        $this->logger->debug('IyZiCo Payment Status', [$checkoutForm->getPaymentStatus()]);
        if ('success' === strtolower($checkoutForm->getPaymentStatus())) {
            $this->logger->debug('IyZiCo Payment Status is success', []);
            $transactionAttempt->order->payment_id = $checkoutForm->getPaymentId();
            $transactionAttempt->order->payment_type = 'IyZiCo Payment'; // TODO decide what to show here
            $transactionAttempt->order->toPaid();
            $transactionAttempt->order->payment_token = $token;
            $transactionAttempt->order->order_date = Carbon::now()->timestamp;
            $transactionAttempt->order->save();

            $retrieveTransactionAttempt = $this->saveTransactionAttempt(
                $request,
                $checkoutForm,
                $transactionAttempt->order->id,
                $transactionAttempt->order,
                'Iyzipay\Model\CheckoutForm::retrieve'
            );

            $this->createTransactionLogs($retrieveTransactionAttempt);
        }
        return $checkoutForm->getRawResult();
    }

    /**
     * verify payment
     *
     * @param int $id
     * @param string $keyType
     * @return array
     */
    public function verifyPayment($paymentID, $paymentType = null): array
    {
        return [];
    }

    /**
     * cancel payment
     *
     * @param Order $order
     * @param string $ip
     * @return array
     * @throws \Exception
     */
    public function cancelPayment(Order $order, string $ip): array
    {
        $transactionAttempt = new TransactionAttempt();
        $transactionAttempt->order_id = $order->id;
        $transactionAttempt->save();
        $request = new CreateCancelRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($transactionAttempt->getConversationId());
        $request->setPaymentId($order->payment_id);
        $request->setIp($ip);

        $cancel = Cancel::create($request, $this->options);

        $transactionAttempt->transaction_request = $request->toJsonString();
        $transactionAttempt->transaction_response = $cancel->getRawResult();
        $transactionAttempt->type = 'App\Models\StoreOrder';
        $transactionAttempt->type_id = $order->store_orders()->first()->id;
        $transactionAttempt->iyzico_type = 'Iyzipay\Model\Cancel::create';
        $transactionAttempt->save();

        if ('success' === strtolower($cancel->getStatus())) {
            return [
                'success'   => true,
                'message'   => __('Cancel request has been sent.')
            ];
        }
        throw new \Exception($cancel->getErrorMessage());
    }

    /**
     * refund entire payment
     *
     * @param StoreOrder $storeOrder
     * @param string $ip
     * @return array
     * @throws \Exception
     */
    public function refundAndCancelPayment(StoreOrder $storeOrder, string $ip)
    {
        $this->logger->debug('Refunding cancelled order ');
        foreach ($storeOrder->order_items as $orderItem) {
            try {
                $orderItemTransaction = $orderItem->order_item_transactions->last();
                $request = new \Iyzipay\Request\CreateRefundRequest();
                $request->setLocale(\Iyzipay\Model\Locale::EN);
                $request->setConversationId($orderItemTransaction->paymentTransactionId);
                $request->setPaymentTransactionId($orderItemTransaction->paymentTransactionId);
                $request->setPrice($orderItemTransaction->paidPrice);
                $request->setCurrency(\Iyzipay\Model\Currency::TL);
                $request->setIp($ip);
                $this->logger->debug('Sending request to refund order API: ', [$request]);
                $refund = \Iyzipay\Model\Refund::create($request, $this->options);
                $this->saveTransactionAttempt(
                    $request,
                    $refund,
                    $orderItem->store_order_id,
                    $orderItem,
                    'Iyzipay\Model\Refund::create'
                );
                if ('success' != strtolower($refund->getStatus())) {
                    $this->logger->debug('Failed refund request ', [$refund->getErrorMessage()]);
                    continue;
                    //TODO will handle using NOTES
                }
                $refundedOrder = $this->saveRefundRecord($refund, null, $orderItem);
                $this->logger->debug('Saved data to order refund table ', [$refundedOrder]);

            } catch (\Exception $exception) {
                $this->logger->debug('Failed refund request ', [$exception]);
            }
        }
        return true;
    }

    /**
     * @param Order $order
     * @return array
     */
    private function getBasketItems(Order $order): array
    {
        $basketItems = [];
        foreach ($order->store_orders as $storeOrder) {
            /** @var OrderItem $orderItem */
            foreach ($storeOrder->order_items as $orderItem) {
                $basketItems[] = $this->createBasketItem($orderItem);
            }
        }
        return $basketItems;
    }

    /**
     * @param OrderItem $orderItem
     * @param array $basketItems
     */
    private function createBasketItem(OrderItem $orderItem): BasketItem
    {
        $store = $orderItem->product->store;
        $basketItem = new \Iyzipay\Model\BasketItem();
        $basketItem->setId($orderItem->id);
        $basketItem->setName($orderItem->product->title);
        /** @var Collection $categoreis */
        $categories = $orderItem->product->categories;
        /** @var Category $category */
        $category = $categories->pop();
        if ($category) {
            $basketItem->setCategory1($category->title);
        }
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $basketItem->setPrice($orderItem->total);
        $basketItem->setSubMerchantKey($store->submerchant_key);
        $basketItem->setSubMerchantPrice($this->deductItemCharges($orderItem));
        $this->logger->debug('Basket Item Price ', [$basketItem->getPrice()]);

        return $basketItem;
    }

    /**
     * @return \Iyzipay\Model\Buyer
     */
    private function getBuyer(Order $order, string $ip): \Iyzipay\Model\Buyer
    {
        $buyer = new \Iyzipay\Model\Buyer();
        $customer = $order->customer;
        $buyer->setId("BY789");
        $buyer->setName($customer->name);
        $buyer->setSurname($this->getSurName($customer));
        $buyer->setGsmNumber($customer->phone ?? '1234567878'); // @saad please use phone number from address
        $buyer->setEmail($customer->email);
        $buyer->setIdentityNumber("74300864791"); // TODO .. task created for this ..
//        $buyer->setLastLoginDate("2015-10-05 12:43:35");
//        $buyer->setRegistrationDate("2013-04-21 15:12:09");
        $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $buyer->setIp($ip);
        /** @var CustomerAddress $address */
        $address = CustomerAddress::findOrFail($order->address_id);
        $buyer->setCity($address->city);
        $buyer->setCountry($address->country);
        $buyer->setZipCode($address->zipcode);
        return $buyer;
    }

    /**
     * @param Order $order
     * @return \Iyzipay\Model\Address
     */
    private function getShippingAddress(Order $order): \Iyzipay\Model\Address
    {
        /** @var CustomerAddress $address */
        $address = CustomerAddress::findOrFail($order->address_id);
        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($order->customer->name);
        $shippingAddress->setCity($address->city);
//        $shippingAddress->setPhone($address->phone);
        $shippingAddress->setCountry($address->country);
        $shippingAddress->setZipCode($address->zipcode);
        $shippingAddress->setAddress($address->address);
        return $shippingAddress;
    }

    /**
     * @param \App\Models\Customer $customer
     * @return false|mixed|string|string[]
     */
    private function getSurName(\App\Models\Customer $customer)
    {
        $surName = $customer->name;
        $part = explode(" ", $customer->name);
        if ($part) {
            $surName = array_pop($part);
        }
        return $surName;
    }

    public function createTransactionLogs(TransactionAttempt $transactionAttempt)
    {

        try{
            $rawResponse = $transactionAttempt->transaction_response;
            $paymentDetails = json_decode($rawResponse);
            $this->createOrderTransactionLog($paymentDetails,$transactionAttempt->id);
            foreach ($paymentDetails->itemTransactions as $itemTransaction) {
                $this->createOrderItemTransactionLog($itemTransaction);
            }

        }catch(\Exception $exc){
            $logger = getLogger('transaction-log');
            $logger->critical("Error Creating Logs from {$transactionAttempt->id} ");
            $logger->critical($exc->getMessage());
        }
    }

    /**
     * @param $paymentDetails
     * @return OrderTransaction
     */
    private function createOrderTransactionLog($paymentDetails, $transactionAttemptId): OrderTransaction
    {
        $orderTransactionLog = new OrderTransaction();

        $orderTransactionLog->transaction_attempt_id = $transactionAttemptId;
        $orderTransactionLog->paymentId = $paymentDetails->paymentId;
        $orderTransactionLog->merchantCommissionRate = $paymentDetails->merchantCommissionRate;
        $orderTransactionLog->merchantCommissionRateAmount = $paymentDetails->merchantCommissionRateAmount;
        $orderTransactionLog->price = $paymentDetails->price;
        $orderTransactionLog->paidPrice = $paymentDetails->paidPrice;
        $orderTransactionLog->installment = $paymentDetails->installment ?? -1;
        $orderTransactionLog->fraudStatus = $paymentDetails->fraudStatus;
        $orderTransactionLog->cardFamily = $paymentDetails->cardFamily;

        $orderTransactionLog->iyziCommissionRateAmount = $paymentDetails->iyziCommissionRateAmount;
        $orderTransactionLog->iyziCommissionFee = $paymentDetails->iyziCommissionFee;
        $orderTransactionLog->cardType = $paymentDetails->cardType;
        $orderTransactionLog->cardAssociation = $paymentDetails->cardAssociation;
        $orderTransactionLog->currency = $paymentDetails->currency;
        $orderTransactionLog->basketId = $paymentDetails->basketId;
        $orderTransactionLog->lastFourDigits = $paymentDetails->lastFourDigits;
        $orderTransactionLog->binNumber = $paymentDetails->binNumber;
        $orderTransactionLog->cardFamily = $paymentDetails->cardFamily;
        $orderTransactionLog->status = $paymentDetails->status;

        $orderTransactionLog->hostReference = $paymentDetails->hostReference;
        $orderTransactionLog->locale = $paymentDetails->locale;
        $orderTransactionLog->systemTime = $paymentDetails->systemTime;

        $orderTransactionLog->conversationId = $paymentDetails->conversationId;
        $orderTransactionLog->token = $paymentDetails->token;
        $orderTransactionLog->paymentStatus = $paymentDetails->paymentStatus;


        $orderTransactionLog->mdStatus = $paymentDetails->mdStatus ?? 'N/A';
        $orderTransactionLog->authCode = $paymentDetails->authCode ?? 'N/A';
        $orderTransactionLog->phase = $paymentDetails->phase ?? 'N/A';
        $orderTransactionLog->save();
        return $orderTransactionLog;
    }

    /**
     * @param $itemTransaction
     * @return OrderItemTransaction
     */
    private function createOrderItemTransactionLog($itemTransaction): OrderItemTransaction
    {
        $orderItemTransaction = new OrderItemTransaction();

        $orderItemTransaction->order_item_id = (int) $itemTransaction->itemId ?? -1;
        $orderItemTransaction->paymentTransactionId = $itemTransaction->paymentTransactionId ?? 'N/A';

        $orderItemTransaction->transactionStatus = $itemTransaction->transactionStatus ?? -1;
        $orderItemTransaction->price = $itemTransaction->price ?? 'N/A';
        $orderItemTransaction->paidPrice = $itemTransaction->paidPrice ?? 'N/A';
        $orderItemTransaction->merchantCommissionRate = $itemTransaction->merchantCommissionRate ?? 'N/A';
        $orderItemTransaction->merchantCommissionRateAmount = $itemTransaction->merchantCommissionRateAmount ?? 'N/A';
        $orderItemTransaction->iyziCommissionRateAmount = $itemTransaction->iyziCommissionRateAmount ?? 'N/A';
        $orderItemTransaction->iyziCommissionFee = $itemTransaction->iyziCommissionFee ?? 'N/A';
        $orderItemTransaction->subMerchantPrice = $itemTransaction->subMerchantPrice ?? 'N/A';
        $orderItemTransaction->subMerchantPayoutRate = $itemTransaction->subMerchantPayoutRate ?? 'N/A';
        $orderItemTransaction->subMerchantPayoutAmount = $itemTransaction->subMerchantPayoutAmount ?? 'N/A';
        $orderItemTransaction->merchantPayoutAmount = $itemTransaction->merchantPayoutAmount ?? 'N/A';


        $orderItemTransaction->convertedPaidPrice = $itemTransaction->convertedPayout->paidPrice ?? 'N/A';
        $orderItemTransaction->convertedIyziConversionRateAmount = $itemTransaction->convertedPayout->iyziCommissionRateAmount ?? 'N/A';
        $orderItemTransaction->convertedIyziCommissionFee = $itemTransaction->convertedPayout->iyziCommissionFee ?? 'N/A';
        $orderItemTransaction->convertedSubMerchantPayoutAmount = $itemTransaction->convertedPayout->subMerchantPayoutAmount ?? 'N/A';
        $orderItemTransaction->convertedMerchantPayoutAmount = $itemTransaction->convertedPayout->merchantPayoutAmount ?? 'N/A';
        $orderItemTransaction->convertedIyziCommissionRateAmount = $itemTransaction->convertedPayout->iyziCommissionRateAmount ?? 'N/A';
        $orderItemTransaction->convertedIyziConversionRate = $itemTransaction->convertedPayout->iyziConversionRateAmount ?? 'N/A';
        $orderItemTransaction->save();
        return $orderItemTransaction;
    }

    /**
     * @param ReturnOrderItems $return_order_item
     * @return string
     * @throws \Exception
     */
    public function createAndSendRefundRequest(ReturnOrderItems $return_order_item)
    {
        $storeOrder = $return_order_item->order_item->store_order;

        $this->logger->debug('Creating refund request ', []);
        $request = new CreateRefundRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId($storeOrder->id);
        $request->setPaymentTransactionId($return_order_item->order_item_transaction->paymentTransactionId);
        $request->setPrice( ($return_order_item->order_item->unit_price + $return_order_item->delivery_fee) * $return_order_item->quantity);
        $refundPrice = ($return_order_item->order_item->unit_price + $return_order_item->delivery_fee) * $return_order_item->quantity;
        $request->setPrice( $refundPrice );
        $this->logger->debug('Sending refund request  of TRY ' . $refundPrice , $return_order_item->toArray());
        $request->setCurrency($storeOrder->order->currency->code); // perhaps will have to try symbol
        $request->setIp($return_order_item->returnRequest->ip);

        $this->logger->debug('Sending refund request ', [$request]);
        $refund = Refund::create($request, $this->options);
        if ('success' != strtolower($refund->getStatus())) {
            $this->logger->debug('Failed refund request ', [$refund->getErrorMessage()]);
            throw new \Exception($refund->getErrorMessage());
        }
        $this->logger->debug('Amount refunded successfully ', [$refund->getRawResult()]);

        $this->saveTransactionAttempt(
            $request,
            $refund,
            $storeOrder->order_id,
            $return_order_item,
            'Iyzipay\Model\Refund::create'
        );
        //saving data to refund record table
        $this->logger->debug('Saving data to order refund table ', [$refund->getRawResult()]);
        $refundedOrder = $this->saveRefundRecord($refund, $return_order_item);
        $this->logger->debug('Saved data to order refund table ', [$refundedOrder]);

        return $refund->getRawResult();
    }


    private function deductItemCharges($orderItem)
    {
        $store = $orderItem->product->store;
        $percentageAmount = ($orderItem->total * $store->commission) / 100;
        return $orderItem->total - $percentageAmount;
    }

    public function createSubMerchantRequest($store)
    {
        $request = new CreateSubMerchantRequest();
        $request->setLocale(Locale::EN);
        $request->setConversationId($this->generateMerchantConversationalId($store));
        $request->setSubMerchantExternalId($store->id);
        $request->setSubMerchantType($this->getStoreType($store));
        $request->setAddress($store->address);
        $request->setTaxOffice($store->tax_office);
        $request->setLegalCompanyTitle($store->legal_name);
        $request->setEmail($store->email);
        $request->setContactName($store->owner->name);
        $request->setContactSurname($store->name);
        $request->setGsmNumber($store->phone);
        $request->setName($store->name);
        $request->setCurrency(Currency::TL);
        if($this->getStoreType($store) == Store::PRIVATE_COMPANY) {
            $request->setIdentityNumber($store->identity_no);
        }
        if($this->getStoreType($store) == Store::LIMITED_STOCK_COMPANY) {
            $request->setTaxNumber($store->tax_id);
        }
        if (!empty($store->iban)) {
            $request->setIban($store->iban);
        }
        $this->logger->debug('Sending  to IzyPay :.  ' . $request->toJsonString());
        $subMerchant = SubMerchant::create($request, $this->options);
//        if ('success' != strtolower($subMerchant->getStatus())) {
//            $this->logger->debug('Failed to create submerchant:.  ' . $subMerchant->getErrorMessage(), [$request->toJsonString()]);
//            $this->logger->debug('Failed to create submerchant:.  ' . $subMerchant->getErrorMessage(), [$subMerchant->getRawResult()]);
//            throw new \Exception($subMerchant->getErrorMessage());
//        }
        $store->submerchant_key = $subMerchant->getSubMerchantKey();
        $store->save();

        $this->logger->debug('Submerchant created successfully and merchant key is: '. $subMerchant->getSubMerchantKey());

        return $subMerchant;
    }

    private function generateMerchantConversationalId($store)
    {
        return sprintf("%s-%s", $store->id, $store->phone);
    }

    private function getStoreType($store)
    {
        switch ($store->type) {
            case Store::LIMITED_STOCK_COMPANY:
                return SubMerchantType::LIMITED_OR_JOINT_STOCK_COMPANY;
                break;
            case Store::PRIVATE_COMPANY:
                return SubMerchantType::PRIVATE_COMPANY;
                break;
            default:
                return SubMerchantType::PERSONAL;
                break;
        }
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function getIyzicoPayment(Order $order)
    {
        $request = new RetrieveCheckoutFormRequest();
        $request->setLocale(Locale::EN);
        $request->setToken($order->payment_token);

        $this->logger->debug('Getting IyZiCo Payment Status', []);
        $paymentResponse = \Iyzipay\Model\CheckoutForm::retrieve($request, $this->options);
        $this->logger->debug('IyZiCo Payment Status', [$paymentResponse->getPaymentStatus()]);
        $this->saveTransactionAttempt(
            $request,
            $paymentResponse,
            $order->id,
            $order,
            'Iyzipay\Model\CheckoutForm::retrieve'
        );

        return $paymentResponse;
    }

    /**
     * @param RetrieveCheckoutFormRequest $request
     * @param Object $paymentResponse
     * @return TransactionAttempt
     */
    private function saveTransactionAttempt($request, $response, $order_id = null, $morph = null, $iyzicoType = null)
    {
        $newTransactionAttempt = new TransactionAttempt();
        $newTransactionAttempt->transaction_request = $request->toJsonString();
        $newTransactionAttempt->transaction_response = $response->getRawResult();
        $newTransactionAttempt->order_id = $order_id;
        $newTransactionAttempt->type = get_class($morph);
        $newTransactionAttempt->type_id = $morph->id;
        $newTransactionAttempt->iyzico_type = $iyzicoType;
        $newTransactionAttempt->save();

        return $newTransactionAttempt;
    }

    /**
     * @param Iyzico Refund Response $refund
     * @param ReturnOrderItems $return_order_item
     * @return OrderRefund
     */
    public function saveRefundRecord($iyzicoRefund, $return_order_item = null, $order_item = null)
    {
        $refund = new OrderRefund();
        $refund->status                 = $iyzicoRefund->getStatus();
        $refund->refund_amount          = $iyzicoRefund->getPrice();
        $refund->refund_type            = $return_order_item != null ? OrderRefund::TYPE_REFUND : OrderRefund::TYPE_CANCELLED;
        $refund->notes                  = $return_order_item != null ? $return_order_item->note : 'N/A';
        $refund->return_order_item_id   = $return_order_item != null ? $return_order_item->id : null;
        $refund->order_item_id          = $order_item != null ? $order_item->id : null;
        $refund->save();
        return $refund;
    }

    public function getRefundedOrderByReturnOrderItem(ReturnOrderItems $return_order_item)
    {
        return OrderRefund::where('return_order_item_id', $return_order_item->id)->first();
    }

    /*
     * @param RetrieveCheckoutFormRequest $request
     * @param Object $paymentResponse
     * @return TransactionAttempt
     */
    public function approveIyzicoMerchantPayment(Order $order)
    {
        foreach($order->order_items as $order_item)
        {
            $transaction = $order_item->order_item_transactions()->latest('id')->first();
            $storeOrder = StoreOrder::find($order_item->store_order_id);
            $this->logger->debug('Creating approve payment request:');
            $request = new CreateApprovalRequest();
            $request->setLocale(\Iyzipay\Model\Locale::EN);
            $request->setConversationId($transaction->paymentTransactionId);
            $request->setPaymentTransactionId($transaction->paymentTransactionId);
            $this->logger->debug('Approve payment request created: '. $request->toJsonString());

            $approval = Approval::create($request, $this->options);
            $this->saveTransactionAttempt(
                $request,
                $approval,
                $order->id,
                $storeOrder,
                'Iyzipay\Model\Approval::create'
            );
            if ('success' != strtolower($approval->getStatus())) {
                $this->logger->debug('Failed to approve payment:.  ' . $approval->getErrorMessage() . $request->toJsonString());
                continue;
                //TODO will handle using NOTES
            }
        }
    }
}
