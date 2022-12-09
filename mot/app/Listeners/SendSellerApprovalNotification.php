<?php

namespace App\Listeners;

use App\Events\SellerApproval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SellerApprovalMail;
use App\Models\Store;
use Monolog\Logger;

class SendSellerApprovalNotification implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'seller-approval-queue';

    /**
     * Handle the event.
     *
     * @param  SellerApproval  $event
     * @return void
     */
    public function handle(SellerApproval $event)
    {
        $sellerApproval = $this->sendSellerApprovalMessage($event->store);
        if ($sellerApproval) {
            $logger = getLogger($this->queue);
            $logger->info('Updated seller approval of ' . $event->store->id . ' is ' , [$event->store->name]);
        }
    }

    /**
     * Send Store approve
     *
     * @param Store $store
     * @return Store
     */
    public function sendSellerApprovalMessage(Store $store) {

        $store->load(['staff']);
        $valueArray = [
            'subject' => __('Seller has been approved.'),
            'message' => Store::STATUS_APPROVED,
        ];
        return Mail::to($store->staff[0]->email)->send(new SellerApprovalMail($valueArray['message'], $valueArray['subject'], $store));
    }
}
