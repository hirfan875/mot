<?php

namespace App\Events;

use App\Models\Store;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SetupIyzicoSubMerchant
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The Store instance.
     *
     * @var \App\Models\Store
     */
    public $store;
    protected $queue = 'create-iyzico-submerchant-queue';

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
