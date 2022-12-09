<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterVerification extends Mailable
{
    use Queueable, SerializesModels;


    public $message;
    public $subject;
    public $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $subject, $customer)
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.customer.verifyemail')->subject($this->subject);
    }
}
