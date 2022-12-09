<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\StoreOrder;

class StoreOrderStatusChange
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $storeOrder;
    protected $queue = 'order-email-queue';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(StoreOrder $storeOrder)
    {
        $this->storeOrder = $storeOrder;
    }
}
