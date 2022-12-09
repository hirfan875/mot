<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $subject;
    public $store;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $subject, $store) {
        $this->message = $message;
        $this->subject = $subject;
        $this->store = $store;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.seller.sellerapproval')->subject($this->subject);
    }
}
