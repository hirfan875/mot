<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * create new user
     *
     * @param array $request
     * @return User
     */
    public function create(array $request): User
    {
        $user = new User();

        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->email_verified_at = now();
        $user->password = Hash::make($request['password']);

        $user->save();
        $user->assignRole($request['roles']);

        return $user;
    }

    /**
     * update user
     *
     * @param User $user
     * @param array $request
     * @return User
     */
    public function update(User $user, array $request): User
    {
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->image = Media::handle($request, 'image', $user);

        if ( !empty($request['password']) ) {
            $user->password = Hash::make($request['password']);
        }

        $user->save();

        return $user;
    }
}
