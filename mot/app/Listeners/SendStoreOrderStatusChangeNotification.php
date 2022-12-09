<?php

namespace App\Listeners;

use App\Events\StoreOrderStatusChange;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusMessage;
use App\Models\StoreOrder;

class SendStoreOrderStatusChangeNotification implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'order-email-queue';

    /**
     * Handle the event.
     *
     * @param  StoreOrderStatusChange  $event
     * @return void
     */
    public function handle(StoreOrderStatusChange $event)
    {
        $orderStatus = $this->sendOrderStatusMessage($event->storeOrder);

        if ($orderStatus) {
            $logger = getLogger($this->queue);
            $logger->info('Updated order status of ' . $event->storeOrder->id . ' is ', [$event->storeOrder->status]);
        }
    }

    /**
     * Send store order status change notification.
     *
     * @param \App\Models\StoreOrder $storeOrder
     * @return void
     */
    public function sendOrderStatusMessage(StoreOrder $storeOrder)
    {
        $storeOrder->load(['order', 'order_items.product', 'customer']);
        $subject = __('Orders status has been changed');
        $message = $storeOrder->status;
        $targetemail = get_option('targetemail');

        return Mail::to($targetemail)->send(new OrderStatusMessage($message, $subject, $storeOrder));
    }
}
