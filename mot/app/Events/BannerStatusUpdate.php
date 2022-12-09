<?php

namespace App\Events;

use App\Models\Banner;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BannerStatusUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The banner instance.
     *
     * @var \App\Models\Banner
     */
    public $banner;

    /**
     * Create a new event instance.
     *
     * @param Banner $banner
     * @return void
     */
    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
    }
}
