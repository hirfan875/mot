<?php

namespace App\Service;

use App\Models\ContactInquiry;
use App\Mail\ContactInquiryReply;
use App\Models\ContactResponse;
use Illuminate\Support\Facades\Mail;

class ContactInquiryService
{
    /**
     * create new contact inquiry
     *
     * @param array $request
     * @return ContactInquiry
     */
    public function create(array $request): ContactInquiry
    {
        $inquiry = new ContactInquiry();

        $inquiry->name = $request['name'];
        $inquiry->email = $request['email'];
        $inquiry->phone = $request['phone'];
        $inquiry->subject = $request['subject'];
        $inquiry->data = $request['data'];

        $inquiry->save();

        return $inquiry;
    }

    /**
     * send contact inquiry reply
     *
     * @param array $request
     * @param ContactInquiry $inquiry
     * @return string
     */
    public function sendReply(array $request, ContactInquiry $inquiry): string
    {
        $reply = new ContactResponse();

        $reply->contact_inquiry_id = $inquiry->id;
        $reply->subject = $request['subject'];
        $reply->message = $request['message'];

        $reply->save();

        Mail::to($inquiry->email)->send(new ContactInquiryReply($request['message'], $request['subject']));
        return __('Email sent successfully.');
    }

    public function getAll($customerId = null)
    {
        $baseInquiry = ContactInquiry::with('replies');
        if ($customerId) {
            $baseInquiry = $baseInquiry->where('customer_id', $customerId);
        }
        return $baseInquiry->get();
    }
}
