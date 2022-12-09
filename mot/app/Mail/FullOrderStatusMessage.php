<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FullOrderStatusMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $subject;
    public $order;

    /**
     * Create a new message instance.
     *
     * @param string $message
     * @param string $subject
     * @return void
     */
    public function __construct($message, $subject, $order)
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.orders.fullorder')->subject($this->subject);
    }
}
