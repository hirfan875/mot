<?php

namespace App\Service;

use App\Models\City;

class CityService
{
    /**
     * create new city
     *
     * @param array $request
     * @param int $country_id
     * @return City
     */
    public function create(array $request, int $country_id, int $state_id): City
    {
        $city = new City();
        $city->country_id = $country_id;
        $city->state_id = $state_id;
        $city->title = $request['title'];
        $city->save();

        return $city;
    }

    /**
     * update city
     *
     * @param City $city
     * @param array $request
     * @return City
     */
    public function update(City $city, array $request): City
    {
        $city->title = $request['title'];
        $city->save();

        return $city;
    }
}
