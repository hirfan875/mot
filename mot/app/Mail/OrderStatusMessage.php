<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $subject;
    public $orders;

    /**
     * Create a new message instance.
     *
     * @param string $message
     * @param string $subject
     * @return void
     */
    public function __construct($message, $subject, $orders)
    {
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
        return $this->markdown('emails.orders.status')->subject($this->subject);
    }
}
