<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\StoreStaff;
use App\Models\Store;
use Illuminate\Contracts\Auth\Authenticatable;

class SellerRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;
    protected $queue = 'seller-verify-queue';

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function __construct(StoreStaff $user)
    {
        $this->user =$user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
