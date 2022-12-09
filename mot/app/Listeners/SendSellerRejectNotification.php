<?php

namespace App\Listeners;

use App\Events\SellerReject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SellerRejectMail;
use App\Models\Store;
use Monolog\Logger;

class SendSellerRejectNotification implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'order-email-queue';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  SellerReject  $event
     * @return void
     */
    public function handle(SellerReject $event)
    {
        $sellerReject = $this->sendSellerRejectMessage($event->store);
        if ($sellerReject) {
            $logger = getLogger($this->queue);
            $logger->info('Updated seller REJECTED of ' . $event->store->id . ' is ' , [$event->store->name]);
        }
    }
    
    /**
     * Send Store reject
     *
     * @param Store $store
     * @return Store
     */
    public function sendSellerRejectMessage(Store $store) 
    {
        $store->load(['staff']);
        $valueArray = [
            'subject' => __('Seller has been rejected.'),
            'message' => Store::STATUS_REJECTED,
        ];
        return Mail::to($store->staff[0]->email)->send(new SellerRejectMail($valueArray['message'], $valueArray['subject'], $store));
    }
}
