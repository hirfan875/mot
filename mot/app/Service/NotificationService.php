<?php

namespace App\Service;

use App\Models\Customer;
use App\Models\UserDevices;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function sendNotification($device_token, $message, $screenA = '/')
    {
//        $stockPartLogo = asset('images/stock-part-logo.png');
        $notification = [
            'sound' => true,
        ];

        $notification['title'] = $message['title'];
        $notification['body'] = $message['description'];
//        $notification['image'] = isset($message['image']) ? $message['image'] : $stockPartLogo;
        
        $data['click_action'] = "FLUTTER_NOTIFICATION_CLICK";
        $data['sound'] = "default";
        $data['status'] = "done";
        $data['screen'] = $screenA;

        /* check data type and send notification to multiple user or single user*/
        if (gettype($device_token) == 'string') {
            $fcmNotification['to'] = $device_token;
        } else {
            $fcmNotification['registration_ids'] = $device_token;
        }
        $fcmNotification['notification'] = $notification;
        $fcmNotification['data'] = $data;
        
        $headers = [
            'Authorization' => 'key=' . config('app.firebase_key'),
            'Content-Type' => 'application/json',
        ];

        $fcmUrl = "https://fcm.googleapis.com/fcm/send";
        $response = Http::withHeaders($headers)->withOptions(['verify' => false])->post($fcmUrl, $fcmNotification);

        return $response->json();
    }

    /**
     * @param $data
     * @return array
     */
    public function setupUserDeviceArray($data)
    {
        $deviceArray = [];
        $deviceArray['device_token'] = $data['device_token'];

        if (isset($data['device_type']) && $data['device_type'] != null) {
            $deviceArray['device_type'] = $data['device_type'];
        }
        $customer_id = null;
        if (Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
        } else if (Auth('sanctum')->check()) {
            $customer_id = Auth('sanctum')->user()->id;
        } else if (isset($data['customer_id']) && $data['customer_id'] != null) {
            $customer_id = $data['customer_id'];
        }
        if ($customer_id != null) {
            $deviceArray['customer_id'] = $customer_id;
        }

        return $deviceArray;
    }

    /**
     * @param $data
     * @return void
     */
    public function saveOrUpdateDeviceToken($data)
    {
        if($data['device_token']){
            $userDevice = UserDevices::firstOrNew(['token' => $data['device_token']]);
            if (isset($data['customer_id']) && $data['customer_id'] != null) {
                $userDevice->customer_id = $data['customer_id'];
            }
            if (isset($data['device_type']) && $data['device_type'] != null) {
                $userDevice->type = $data['device_type'];
            }

            $userDevice->save();
        }
    }

    public function saveNotifications($data)
    {
        $Notification = new Notification();
        if (isset($data['customer_id']) && $data['customer_id'] != null) {
            $Notification->customer_id = $data['customer_id'];
        }
        if (isset($data['type']) && $data['type'] != null) {
            $Notification->type = $data['type'];
        }
        $Notification->language_id = $data['language_id'];
        $Notification->title = $data['title'];
        $Notification->description = $data['description'];
        $Notification->token = $data['token'];

        $Notification->save();
    }


    /**
     * @param $device_token
     * @return mixed
     */
    public function getNotificationsListForGuest($device_token)
    {
        return Notification::where('token', $device_token)->get();
    }

    /**
     * @param Customer $customer
     * @return mixed
     */
    public function getNotificationsListForRegistered(Customer $customer)
    {
        return Notification::where('customer_id', $customer->id)->get();
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        Notification::where('id', $id)->delete();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function changeLanguage($data)
    {
        $userDevice = UserDevices::firstOrNew(['token' => $data['device_token']]);
        $userDevice->lang = $data['lang_code'];
        $userDevice->save();

        return $userDevice;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function changeSettingToggle($data)
    {
        $userDevice = UserDevices::firstOrNew(['token' => $data['device_token']]);
        if ($data['type'] == 'general') {
            $userDevice->is_general_notifications = $data['toggle'];
        }

        if ($data['type'] == 'order') {
            $userDevice->is_order_notifications = $data['toggle'];
        }
        $userDevice->save();

        return $userDevice;
    }
}
