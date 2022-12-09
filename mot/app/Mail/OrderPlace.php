<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlace extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $subject;
    public $orders;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $subject, $orders) {
        $this->message = $message;
        $this->subject = $subject;
        $this->orders = $orders;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.orders.orderplaced')->subject($this->subject);
    }
}
