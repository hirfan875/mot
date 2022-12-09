<?php

namespace App\Service;

use App\Models\Store;
use App\Models\StoreStaff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class StoreStaffService
{
    /**
     * create new staff user
     *
     * @param array $request
     * @param Store $store
     * @return StoreStaff
     */
    public function create(array $request, Store $store): StoreStaff
    {
        $staff = new StoreStaff();

        $staff->store_id = $store->id;
        $staff->name = $request['name'];
        $staff->email = $request['email'];
        $staff->phone = $request['phone'];
        $staff->password = Hash::make($request['password']);

        $staff->save();

        return $staff;
    }

    /**
     * create new staff owner
     *
     * @param array $request
     * @param Store $store
     * @return StoreStaff
     */
    public function createOwner(array $request, Store $store): StoreStaff
    {
        $staff = new StoreStaff();

        $staff->store_id = $store->id;
        $staff->is_owner = true;
        $staff->name = $request['name'];
        $staff->email = $request['email'];
        $staff->phone = $request['phone'];
        $staff->password = Hash::make($request['password']);

        $staff->save();
        event(new Registered($staff));

        return $staff;
    }

    /**
     * update staff user
     *
     * @param StoreStaff $staff
     * @param array $request
     * @return StoreStaff
     */
    public function update(StoreStaff $staff, array $request): StoreStaff
    {
        $staff->name = $request['name'];
        $staff->email = $request['email'];
        $staff->phone = $request['phone'];

        if ( isset($request['password']) && !empty($request['password']) ) {
            $staff->password = Hash::make($request['password']);
        }

        $staff->save();

        return $staff;
    }

    /**
     * update staff user profile
     *
     * @param StoreStaff $staff
     * @param array $request
     * @return StoreStaff
     */
    public function updateProfile(StoreStaff $staff, $request): StoreStaff
    {
        $staff->name = $request['name'];
        $staff->email = $request['email'];
        $staff->phone = $request['phone'];
        $staff->image = Media::handle($request, 'image', $staff);

        if ( isset($request['password']) && !empty($request['password']) ) {
            $staff->password = Hash::make($request['password']);
        }

        $staff->save();

        return $staff;
    }
}
