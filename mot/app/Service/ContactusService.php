<?php

namespace App\Service;

use App\Models\ContactInquiry;
use App\Models\UserDevices;
use App\Service\NotificationService;

class ContactusService
{
    /**
     * @param array $data
     * @return ContactInquiry
     */

    public function create(array $data, NotificationService $notificationService)
    {
        $ContactUs = new ContactInquiry();
        
        $customer_id = '';
        $ContactUs->name = $data['name'];
        $ContactUs->email = $data['email'];
        $ContactUs->phone = $data['phone'];
        if(isset($data['subject'])) {
            $ContactUs->subject = $data['subject'];
        }
        $ContactUs->data = $data['message'];
        if (isset($data['customer_id']) && $data['customer_id'] != null) {
            $ContactUs->customer_id = $data['customer_id'];
            $customer_id = $data['customer_id'];
            $userDevice = UserDevices::where('customer_id', $data['customer_id'])->where('is_general_notifications',true)->latest()->first();
        } else {
            
            if(isset($data['device_token'])){
            $userDevice = UserDevices::where('token', $data['device_token'])->where('is_general_notifications',true)->latest()->first();
             }
        }

        $ContactUs->save();
        
         if(isset($userDevice->token)){

            $title = _("Contact Inquiry");
            $description = __("we'll get back to you soon notification will be received after approval and contacted");
            $type = 'general' ;
            $lang_id = 1;
            $token = $userDevice->token;

            $message = [
                'title' => $title,
                'description' => $description,
                'customer_id' => $customer_id,
                'type' => $type,
                'language_id' => $lang_id,
                'token' => $token,
            ];
            $screenA = '/inquiry';
            $notificationService->saveNotifications($message);
            $notificationService->sendNotification($token, $message, $screenA);
         }
        return $ContactUs;
    }
}
