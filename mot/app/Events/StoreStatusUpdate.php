<?php

namespace App\Events;

use App\Models\Store;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoreStatusUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The store instance.
     *
     * @var \App\Models\Store
     */
    public $store;

    /**
     * Create a new event instance.
     *
     * @param Store $store
     * @return void
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }
}
