<?php

namespace App\Policies;

use App\Models\Coupon;
use App\Models\StoreStaff;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouponPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the vendor can view the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\Coupon  $coupon
     * @return mixed
     */
    public function canView(StoreStaff $staff, Coupon $coupon)
    {
        return $staff->store_id === $coupon->store_id;
    }

    /**
     * Determine whether the vendor can update the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\Coupon  $coupon
     * @return mixed
     */
    public function canUpdate(StoreStaff $staff, Coupon $coupon)
    {
        return $staff->store_id === $coupon->store_id;
    }

    /**
     * Determine whether the vendor can delete the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\Coupon  $coupon
     * @return mixed
     */
    public function canDelete(StoreStaff $staff, Coupon $coupon)
    {
        return $staff->store_id === $coupon->store_id;
    }
}
