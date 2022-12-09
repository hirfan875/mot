<?php

namespace App\Service\PaymentMethods;

use App\Models\Order;
use App\Service\TransactionService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PayU implements PaymentGateway
{
    protected $pos_id;
    protected $client_id;
    protected $client_secret;
    protected $authorizeUrl;
    protected $sendPaymentUrl;
    protected $notifyUrl;
    protected $errorUrl;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->pos_id = config('payment.payu.pos_id');
        $this->client_id = config('payment.payu.client_id');
        $this->client_secret = config('payment.payu.client_secret');
        $this->authorizeUrl = config('payment.payu.authorize_url');
        $this->sendPaymentUrl = config('payment.payu.payment_url');
        $this->notifyUrl = route('myfatoorah.verify');
        $this->errorUrl = route('myfatoorah.failure');
    }

    /**
     * authorize user
     *
     * @return array
     */
    public function authorize()
    {
        $response = Http::get($this->authorizeUrl, [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        ]);

        if ($response->failed()) {
            return [
                'success' => false,
                'message' => $response['error_description']
            ];
        }

        return [
            'success' => true,
            'access_token' => $response['access_token']
        ];
    }

    /**
     * process payment
     *
     * @param int $order_id
     * @return array
     */
    public function processPayment(int $order_id)
    {
        $authorize = $this->authorize();
        if (!$authorize['success']) {
            return $authorize;
        }

        $order = Order::with(['customer', 'order_items.product'])->findOrFail($order_id);
        $transactionService = new TransactionService();
        $transaction = $transactionService->createAttempt($order->id);
        $post_data = $this->paymentData($order, $transaction->id);

        $response = Http::withToken($authorize['access_token'])->post($this->sendPaymentUrl, $post_data);

        dd($response->json());

        if ($response->failed()) {
            return $response;
        }

        //$transactionService->updateAttempt($transaction, $response['Data']);

        return $response;
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
     * @param mixed $paymentID
     * @return array
     */
    public function cancelPayment($paymentID): array
    {
        return [];
    }

    /**
     * set payment data
     *
     * @param Order $order
     * @param int $transaction_id
     * @return array
     */
    private function paymentData(Order $order, int $transaction_id): array
    {
        return [
            'extOrderId' => $transaction_id,
            'notifyUrl' => $this->notifyUrl,
            'customerIp' => \request()->ip(),
            'merchantPosId' => $this->pos_id,
            'description' => 'Mall Of Turkey',
            'currencyCode' => 'PLN',
            'totalAmount' => "21000",
            'buyer' => $this->buyerAddress($order),
            'products' => $this->orderProducts($order)
        ];
    }

    /**
     * set payment buyer address
     *
     * @param Order $order
     * @return array
     */
    private function buyerAddress(Order $order): array
    {
        $customerName = split_name($order->customer->name);

        return [
            'email' => $order->customer->email,
            'phone' => $order->customer->phone,
            'firstName' => $customerName[0],
            'lastName' => $customerName[1]
        ];
    }

    /**
     * set order products
     *
     * @param Order $order
     * @return array
     */
    private function orderProducts(Order $order): array
    {
        $items = [];
        foreach ($order->order_items as $item) {
            $items[] = [
                'name' => $item->product->title,
                'quantity' => $item->quantity,
                'unitPrice' => "21000"
            ];
        }

        /* if (!empty($order->delivery_fee)) {
            $items[] = [
                'name' => 'Delivery Fee',
                'quantity' => 1,
                'unitPrice' => number_format($order->delivery_fee, 3)
            ];
        }

        if (!empty($order->tax)) {
            $items[] = [
                'name' => 'Tax',
                'quantity' => 1,
                'unitPrice' => number_format($order->tax, 3)
            ];
        } */

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
            'errors' => $response['ValidationErrors']
        ];
    }
}
