<?php

namespace App\Service;

use App\Models\Store;
use App\Models\StoreAddress;

class StoreAddressService
{
    /**
     * create new address
     *
     * @param array $request
     * @param int $store_id
     * @return StoreAddress
     */
    public function create(array $request, int $store_id): StoreAddress
    {
        $address = new StoreAddress();

        $address->store_id = $store_id;
        $this->setAddressFields($address, $request);
        $address->save();

        return $address;
    }

    /**
     * update address
     *
     * @param StoreAddress $address
     * @param array $request
     * @return StoreAddress
     */
    public function update(StoreAddress $address, array $request): StoreAddress
    {
        $this->setAddressFields($address, $request);
        $address->save();

        return $address;
    }

    /**
     * update or create address
     *
     * @param int $store_id
     * @param array $request
     * @return StoreAddress
     */
    public function updateOrCreate(int $store_id, array $request): StoreAddress
    {
        $address = StoreAddress::whereStoreId($store_id)->first();
        if (!$address) {
            $address = new StoreAddress();
            $address->store_id = $store_id;
        }

        $this->setAddressFields($address, $request);
        $address->save();

        return $address;
    }

    /**
     * set address fields
     *
     * @param StoreAddress $address
     * @param array $request
     * @return void
     */
    protected function setAddressFields(StoreAddress $address, array $request): void
    {
        $address->name = $request['name'];
        $address->phone = $request['phone'];
        $address->address = $request['address'];
        $address->address2 = $request['address2'];
        $address->address3 = $request['address3'];
        $address->city = $request['city'];
        $address->state = $request['state'];
        $address->zipcode = $request['zipcode'];
        $address->country = $request['country'];
    }
}
