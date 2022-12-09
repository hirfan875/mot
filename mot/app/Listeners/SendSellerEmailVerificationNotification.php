<?php

namespace App\Listeners;

use App\Events\SellerRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\Register;
use App\Models\StoreStaff;

class SendSellerEmailVerificationNotification implements ShouldQueue {

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'seller-verify-queue';

    /**
     * Handle the event.
     *
     * @param  SellerRegistered  $event
     * @return void
     */
    public function handle(SellerRegistered $event) {
        
        if ($event->user instanceof MustVerifyEmail && !$event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
        $sellerRegistration = $this->sellerRegistration($event->user);
    }

    public function sellerRegistration(StoreStaff $user) 
    {
        $valueArray = [
            'subject' => __('Welcome on Mall of Turkeya.'),
            'message' => __('You are successfully registered.'),
        ];
        Mail::to($user->email)->send(new Register($valueArray['message'], $valueArray['subject'], $user));
    }

}
