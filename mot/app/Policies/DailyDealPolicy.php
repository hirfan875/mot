<?php

namespace App\Policies;

use App\Models\DailyDeal;
use App\Models\StoreStaff;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyDealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the vendor can view the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\DailyDeal  $deal
     * @return mixed
     */
    public function canView(StoreStaff $staff, DailyDeal $deal)
    {
        return $staff->store_id === $deal->store_id;
    }

    /**
     * Determine whether the vendor can update the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\DailyDeal  $deal
     * @return mixed
     */
    public function canUpdate(StoreStaff $staff, DailyDeal $deal)
    {
        return $staff->store_id === $deal->store_id;
    }

    /**
     * Determine whether the vendor can delete the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\DailyDeal  $deal
     * @return mixed
     */
    public function canDelete(StoreStaff $staff, DailyDeal $deal)
    {
        return $staff->store_id === $deal->store_id;
    }
}
