<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\SetupIyzicoSubMerchant;
use App\Service\PaymentMethods\IyzicoPayment;

class RegisterIyzicoSubMerchant implements ShouldQueue
{
    public $queue = 'create-iyzico-submerchant-queue';

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SetupIyzicoSubMerchant $event)
    {
        $store = $event->store;
        $logger = getLogger($this->queue);
        try {
            if (!$store->hasSubMerchantKey()) {
                $izycoService = new IyzicoPayment();
                $merchant = $izycoService->createSubMerchantRequest($store);
            }
        }catch(\Exception $exc) {
            // TODO Add To Admin Error Logs
            $logger->critical($exc->getMessage());
            throw  $exc;
        }
    }
}
