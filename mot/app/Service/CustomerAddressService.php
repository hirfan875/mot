<?php

namespace App\Service;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class CustomerAddressService
{
    // TODO should be static .. but I cant get it to pass through phpStorm Inspection
    protected $allowedFields = ['full', 'name', 'phone', 'address', 'address2', 'address3', 'aera', 'block', 'street_number',  'house_apartment', 'city', 'state', 'country', 'zipcode'];

    /**
     * get all customer addresses
     *
     * @param int $customer_id
     * @return Collection
     */
    public function getAllAddresses(int $customer_id): Collection
    {
        return CustomerAddress::whereCustomerId($customer_id)->get();
    }

    /**
     * create new address
     *
     * @param array $request
     * @param int $customer_id
     * @return CustomerAddress
     */
    public function create(array $request, int $customer_id): CustomerAddress
    {
        $logger = getLogger('customer-address');
//        $logger->debug('creating address for ' . $customer_id , $request);
        $address = new CustomerAddress();
        $address->customer_id = $customer_id;
        $this->setAddressFields($address, Arr::only($request, $this->allowedFields));
        $address->save();

        return $address;
    }

    /**
     * update address
     *
     * @param CustomerAddress $address
     * @param array $request
     * @return CustomerAddress
     */
    public function update(CustomerAddress $address, array $request): CustomerAddress
    {
        $this->setAddressFields($address, Arr::only($request, $this->allowedFields));
        $address->save();

        return $address;
    }

    /**
     * make address as default
     *
     * @param CustomerAddress $address
     * @return void
     */
    public function makeDefault(CustomerAddress $address)
    {
        // update all other addresses status
        CustomerAddress::query()
            ->where('id', '<>', $address->id)
            ->where('customer_id', $address->customer_id) // better define a scope
            ->update(['is_default' => false]);

        // set selected address as default
        $address->is_default = true;
        $address->save();
    }

    /**
     * @param CustomerAddress $address
     * @param array $addressData
     */
    public function setAddressFields(CustomerAddress $address, array $addressData): void
    {
        $address->name = $addressData['name'];
        $address->phone = $addressData['phone'];
        $address->address = $addressData['address'];
        $address->aera = isset($addressData['aera']) ? $addressData['aera'] : '';
        $address->block = isset($addressData['block']) ? $addressData['block'] : '';
        $address->street_number = isset($addressData['street_number']) ? $addressData['street_number'] : '';
        $address->house_apartment = isset($addressData['house_apartment']) ? $addressData['house_apartment'] : '';
        $address->city = $addressData['city'];
        $address->state = $addressData['state'];
        if(isset($addressData['zipcode'])){
            $address->zipcode = $addressData['zipcode'];
        }
        $address->country = $addressData['country'];
    }

    /**
     * @param address_id $address_id
     * @return CustomerAddress
     */
    public function getById($address_id): CustomerAddress
    {
        $address = CustomerAddress::query()->where('id', $address_id)->first();
        return $address;
    }

    /**
     * @param array $request
     * @param Customer $customer
     * @return CustomerAddress
     */
    public function createGuestAddress(array $request, Customer $customer): CustomerAddress
    {
        $address = $this->create($request, $customer->id);
        return $address;
    }

    public function updateGuestAddress(array $request, Customer $customer): CustomerAddress
    {
        $address = $this->getGuestAddress($customer);
        $address = $this->update($address, $request);
        return $address;
    }

    public function getGuestAddress(Customer $customer): CustomerAddress
    {
        return CustomerAddress::query()
            ->where('customer_id', $customer->id)
            ->orderBy('id', 'desc')
            ->first();
    }
}
