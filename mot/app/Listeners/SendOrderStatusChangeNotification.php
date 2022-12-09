<?php

namespace App\Listeners;

use App\Events\OrderStatusChange;
use App\Mail\FullOrderStatusMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;

class SendOrderStatusChangeNotification implements ShouldQueue
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
     * @param  OrderStatusChange  $event
     * @return void
     */
    public function handle(OrderStatusChange $event)
    {
        $orderStatus = $this->sendOrderStatusMessage($event->order);

        if ($orderStatus) {
            $logger = getLogger($this->queue);
            $logger->info('Updated order status of ' . $event->storeOrder->id . ' is ', [$event->storeOrder->status]);
        }
    }

    /**
     * Send order status change message to customer.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function sendOrderStatusMessage(Order $order)
    {
        $order->load(['customer', 'order_items.product', 'currency']);
        $subject = __('Orders status has been changed');
        $message = $order->status;

        return Mail::to($order->customer->email)->send(
            new FullOrderStatusMessage($message, $subject, $order)
        );
    }
}
