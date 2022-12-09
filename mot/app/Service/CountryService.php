<?php

namespace App\Service;

use App\Models\Country;
use Illuminate\Support\Collection;

class CountryService
{
    /**
     * create new country
     *
     * @param array $request
     * @return Country
     */
    public function create(array $request): Country
    {
        $country = new Country();

        // set this country as default
        if ( isset($request['is_default']) ) {
            $country->is_default = $request['is_default'];
        }

        $country->title = $request['title'];
        $country->code = $request['code'];

        $country->save();

        return $country;
    }

    /**
     * update country
     *
     * @param Country $country
     * @param array $request
     * @return Country
     */
    public function update(Country $country, array $request): Country
    {
        $country->title = $request['title'];
        $country->code = $request['code'];

        $country->save();

        return $country;
    }

    /**
     * get all active countries
     *
     * @return Collection
     */
    public function getActiveCountries(): Collection
    {
        return Country::whereStatus(true)->orderBy('is_default', 'desc')->get();
    }

    /**
     * get all active countries
     *
     * @return Collection
     */
    public function getCountryByCode($code): Country
    {
        return Country::whereCode($code)->first();
    }
}
