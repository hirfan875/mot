<?php

namespace App\Policies;

use App\Models\StoreQuestion;
use App\Models\StoreStaff;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreQuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the vendor can view the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\StoreQuestion  $question
     * @return mixed
     */
    public function canView(StoreStaff $staff, StoreQuestion $question)
    {
        return $staff->store_id === $question->store_id;
    }

    /**
     * Determine whether the vendor can delete the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\StoreQuestion  $question
     * @return mixed
     */
    public function canDelete(StoreStaff $staff, StoreQuestion $question)
    {
        return $staff->store_id === $question->store_id;
    }

    /**
     * Determine whether the vendor can archive the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\StoreQuestion  $question
     * @return mixed
     */
    public function canArchive(StoreStaff $staff, StoreQuestion $question)
    {
        return $staff->store_id === $question->store_id;
    }
}
