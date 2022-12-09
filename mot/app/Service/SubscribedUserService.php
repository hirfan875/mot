<?php

namespace App\Service;
use App\Models\SubscribedUser;

class SubscribedUserService
{
    /**
     * @param array $request
     * @return SubscribedUser
     */
    public function create(array $request)
    {
        $subscribedUser = new SubscribedUser();
        $subscribedUser->email = $request['email'];
        $subscribedUser->save();

        return $subscribedUser;
    }

    /**
     * @param $email
     * @return bool
     */
    public function isAlreadySubscribed($email)
    {
        $row = SubscribedUser::where('email', $email)->first();
        if ($row == null) {
            return false;
        }
        return true;
    }
}
