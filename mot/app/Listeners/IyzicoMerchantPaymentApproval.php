<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderDelivered;
use App\Service\PaymentMethods\IyzicoPayment;

class IyzicoMerchantPaymentApproval
{
    public $queue = 'merchant-payment-arroved';

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderDelivered $event)
    {
        try {
            $order = $event->order;
            $paymentService = new IyzicoPayment;
            $payment = $paymentService->getIyzicoPayment($order);
            if ('success' !== strtolower($payment->getStatus())) {
                throw new \Exception($payment->getErrorMessage());
            }

            $paymentService->approveIyzicoMerchantPayment($order);

        } catch(\Exception $exc) {
            throw  $exc;
        }
    }
}
