<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\NotificationResource;
use App\Models\UserNotification;
use App\Service\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends BaseController
{
    public function index(Request $request, NotificationService $notificationService)
    {
        $customer = getCustomer();
        if ($customer != null) {
            $notifications = NotificationResource::collection($notificationService->getNotificationsListForRegistered($customer));
            return $this->sendResponse($notifications, __('Data loaded successfully'));
        }

        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $notifications = NotificationResource::collection($notificationService->getNotificationsListForGuest($request->device_token));
        return $this->sendResponse($notifications, __('Data loaded successfully'));
    }

    /**
     * @param Request $request
     * @param NotificationService $notificationService
     * @return mixed
     */
    public function delete(Request $request, NotificationService $notificationService)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $notificationService->delete($request->id);
        return $this->sendResponse([], __('Record has been deleted successfully'));
    }

    /**
     * @param NotificationService $notificationService
     * @return void
     */
    public function testNotification(NotificationService $notificationService)
    {
        $title = "Winter sale is here!";
        $description = "Flat 20% off on all items";
        $customer_id = 214;
        $lang_id = 1;
        $token = "cfykce5OT2mhwhPLC6ln1e:APA91bEBhSa2zUhSaqm5TeuKuLUOR77zfFioBZZihSLGcsNHDrvnMoA0n9AN-y1MuU9WY3dzdDkPdvltq_xxgvvo0lWlc2e8vZAMhFhtkNM5KbQ85U8vPnEcvdJpakUYxgW8yNHuCV50";
        $message = [
            'title' => $title,
            'description' => $description,
        ];
        $notificationService->sendNotification($token, $message);
    }

    /**
     * @param Request $request
     * @param NotificationService $notificationService
     * @return \Illuminate\Http\Response
     */
    public function changeLanguage(Request $request, NotificationService $notificationService)
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
            'lang_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $notificationService->changeLanguage($request->all());
        return $this->sendResponse([], __('Language has been changed successfully'));
    }

    /**
     * @param Request $request
     * @param NotificationService $notificationService
     * @return \Illuminate\Http\Response
     */
    public function changeToggle(Request $request, NotificationService $notificationService)
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
            'type' => 'required',
            'toggle' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $notificationService->changeSettingToggle($request->all());
        return $this->sendResponse([], __('Toggle settings has been changed successfully'));
    }
}
