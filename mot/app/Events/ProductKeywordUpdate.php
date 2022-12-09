<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductKeywordUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The product instance.
     *
     * @var \App\Models\Product
     */
    public $product;
    protected $queue = 'update-keyword-queue';

    /**
     * Create a new event instance.
     *
     * @param Product $product
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
