<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusMessage;
use App\Mail\OrderPlace;
use App\Models\Order;
use Monolog\Logger;

class SendOrderPlacedNotification implements ShouldQueue
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
     * @param  OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $orderPlaced = $this->sendOrderPlacedMessage($event->order);
        if ($orderPlaced) {
            $logger = getLogger('order-placed-queue');
            $logger->info('Updated order Placed of ' . $event->order->id . ' is ' , [$event->order->status]);
        }
    }


    public function sendOrderPlacedMessage(Order $order) {

        $order->load(['customer', 'currency', 'store_orders.seller', 'store_orders.order_items.product']);
        $valueArray = [
            'subject' => __('Order Confirmation Email.'),
            'message' => $order->status,
        ];

        return Mail::to($order->customer->email)->send(new OrderPlace($valueArray['message'], $valueArray['subject'], $order));
    }
}
