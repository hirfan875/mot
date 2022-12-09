<?php

namespace App\Jobs;

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
class CancelOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * uploaded file name
     *
     * @var Order
     */
    public $order;
    /** @var string $ip */
    public $ip;
    public $queue;

    /**
     * Create a new CancelOrder job.
     *
     * @param Order $order
     * @param string $ip
     */
    public function __construct(Order $order,$ip)
    {
        $this->queue = 'cancel-order-queue';
        $this->onQueue($this->queue); // TODO find one place to keep all queue names
        $this->order = $order;
        $this->ip = $ip; //"127.0.0.1"; // TODO fix the IP .. get it from somewhere that is accessible to Order Service
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logger = getLogger('cancel-order-listener', Logger::DEBUG, 'logs/iyzico-payment.log');
        $logger->debug('Cancelling An Order ', [$this->order->id, $this->order->order_number]);
        $paymentService = new MyFatoorah();
        try {
            // check if both storeOrder and order are cancelled
            if(!$this->order->allStoreOrderCancelled() && $this->order->getStatus() != Order::CANCELLED_ID) {
                throw new \Exception(__('Cannot cancel this order'));
            }

            /*check if payment gateway is "payU" */
            if($this->order->payment_type == $this->order::PAYU) {
                $paymentService = new IyzicoPayment();

                $logger->debug('Getting an order from iyzico', [$this->order->id, $this->order->order_number]);
                $paymentDetails = $paymentService->getIyzicoPayment($this->order);

                if('success' !== strtolower($paymentDetails->getPaymentStatus())) {
                    // throw new \Exception(__('Payment not found'));
                    $logger->debug('Payment not found. This issue must be looked at ', [$this->order->id, $this->order->order_number]);
                }
            }else{  // we should get rid of this else later ... its done currently because of invalid data

            /*check if payment gateway is "MyFatoorah" */
//            if($this->order->payment_type == $this->order::MYFATOORAH) {
                $paymentService = new MyFatoorah();
                $paymentDetails = $paymentService->getMyFatoorahPayment($this->order);

                if('paid' !== strtolower($paymentDetails['data']['InvoiceStatus']) && !$paymentDetails['success']) {
                    // throw new \Exception(__('Payment not found'));
                    $logger->debug('Payment not found. This issue must be looked at ', [$this->order->id, $this->order->order_number]);
                }
            }

            if($this->order->canCancellable()) {
                $logger->debug('Cancel order', [$this->order->id, $this->order->order_number]);
                $paymentService->cancelPayment($this->order, $this->ip);
                return true;
            }

        } catch (\Exception $exception) {
            $logger->critical($exception->getMessage(), [$this->order->id, $this->order->order_number]);
            throw $exception;
        }
    }

    public function failed($exception)
    {
        $logger = getLogger($this->queue);
        $exception->getMessage();
        $logger->critical('Job failed: '.$exception->getMessage(), [$this->order->id, $this->order->order_number]);
    }
}
