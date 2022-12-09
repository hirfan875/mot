<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\StoreStaff;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the vendor can view the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function canView(StoreStaff $staff, Product $product)
    {
        return $staff->store_id === $product->store_id;
    }

    /**
     * Determine whether the vendor can update the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function canUpdate(StoreStaff $staff, Product $product)
    {
        return $staff->store_id === $product->store_id;
    }

    /**
     * Determine whether the vendor can delete the model.
     *
     * @param  \App\Models\StoreStaff  $staff
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function canDelete(StoreStaff $staff, Product $product)
    {
        return $staff->store_id === $product->store_id;
    }
}
