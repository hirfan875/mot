<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactInquiryReply extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param string $message
     * @param string $subject
     * @return void
     */
    public function __construct($message, $subject)
    {
        $this->message = $message;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact-inquiry.reply')->subject($this->subject);
    }
}
