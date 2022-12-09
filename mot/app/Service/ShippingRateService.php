<?php

namespace App\Service;

use App\Models\ShippingRate;
use Illuminate\Support\Collection;

class ShippingRateService
{
    /**
     * create new shippingRate
     *
     * @param array $request
     * @return ShippingRate
     */
    public function create(array $request, int $country_id): ShippingRate
    {
        $shippingRate = new ShippingRate();
        $shippingRate->country_id = $country_id;
        $shippingRate->weight = $request['weight'];
        $shippingRate->rate = $request['rate'];
        $shippingRate->save();

        return $shippingRate;
    }

    /**
     * update State
     *
     * @param ShippingRate $shippingRate
     * @param array $request
     * @return State
     */
    public function update(ShippingRate $shippingRate, array $request): ShippingRate
    {
        $shippingRate->weight = $request['weight'];
        $shippingRate->rate = $request['rate'];
        $shippingRate->save();

        return $shippingRate;
    }

    /**
     * get all active countries
     *
     * @return Collection
     */
    public function getActiveStates(): Collection
    {
        return ShippingRate::whereStatus(true)->orderBy('is_default', 'desc')->get();
    }

    /**
     * get all active countries
     *
     * @return Collection
     */
    public function getStateByCode($code): ShippingRate
    {
        return ShippingRate::whereCode($code)->first();
    }
}
