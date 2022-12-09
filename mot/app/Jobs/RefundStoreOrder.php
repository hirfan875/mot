<?php

namespace App\Jobs;

use App\Models\StoreOrder;
use App\Models\Order;
use App\Service\PaymentMethods\IyzicoPayment;
use App\Service\PaymentMethods\MyFatoorah;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Monolog\Logger;


/**
 * Currently cancels entire order. In future when an order contains multiple storeOrder, we will have to refund partial order.
 * Class CancelOrder
 * @package App\Jobs
 */
class RefundStoreOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Store Order that will be refunded
     *
     * @var StoreOrder
     */
    public $storeOrder;

    /** @var string $ip */
    public $ip;

    /**
     * Create a new RefundOrder job.
     *
     * @param StoreOrder $storeOrder
     * @param string $ip
     */
    public function __construct(StoreOrder $storeOrder, $ip = null)
    {
        $this->onQueue('refund-order-queue');
        $this->storeOrder = $storeOrder;
        $this->ip = $ip; //'127.0.0.1'; // TODO find a way to get the IP
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logger = getLogger('refund-order-listener', Logger::DEBUG, 'logs/iyzico-payment.log');
        /** check if payment gateway is "payU" then calling payu APIs */
        if($this->storeOrder->order->payment_type == Order::PAYU)
        {
            $paymentService = new IyzicoPayment();
            $paymentDetails = $paymentService->getIyzicoPayment($this->storeOrder->order);

            if(strtolower($paymentDetails->getPaymentStatus()) !== 'success') {
                $logger->debug('Payment not found. This issue must be looked at ', [$this->order->id, $this->order->order_number]);
                throw new \Exception(__('Payment not found'));
            }
        }

        /** check if payment gateway is "MyFatoorah" then call Myfatoorah APIs*/
        if ($this->storeOrder->order->payment_type == Order::MYFATOORAH) {
            $paymentService = new MyFatoorah();
            $paymentDetails = $paymentService->getMyFatoorahPayment($this->storeOrder->order);

            if ('paid' !== strtolower($paymentDetails['data']['InvoiceStatus']) && !$paymentDetails['success']) {
                $logger->debug('Payment not found. This issue must be looked at ', [$this->order->id, $this->order->order_number]);
                throw new \Exception(__('Payment not found'));
            }
        }

        $paymentService->refundAndCancelPayment($this->storeOrder, $this->ip);
    }

    public function failed(\Throwable $exception)
    {
        $logger = getLogger('refund-order-listener', Logger::DEBUG, 'logs/iyzico-payment.log');
        $logger->critical('Job failed: '.$exception->getMessage(), [$this->order->id, $this->order->order_number]);
    }
}
