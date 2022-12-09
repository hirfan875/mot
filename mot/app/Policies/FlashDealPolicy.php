<?php

namespace App\Policies;

use App\Models\FlashDeal;
use App\Models\StoreStaff;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlashDealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the vendor can view the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\FlashDeal  $deal
     * @return mixed
     */
    public function canView(StoreStaff $staff, FlashDeal $deal)
    {
        return $staff->store_id === $deal->store_id;
    }

    /**
     * Determine whether the vendor can update the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\FlashDeal  $deal
     * @return mixed
     */
    public function canUpdate(StoreStaff $staff, FlashDeal $deal)
    {
        return $staff->store_id === $deal->store_id;
    }

    /**
     * Determine whether the vendor can delete the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\FlashDeal  $deal
     * @return mixed
     */
    public function canDelete(StoreStaff $staff, FlashDeal $deal)
    {
        return $staff->store_id === $deal->store_id;
    }
}
