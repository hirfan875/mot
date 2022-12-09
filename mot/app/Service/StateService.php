<?php

namespace App\Service;

use App\Models\State;
use Illuminate\Support\Collection;

class StateService
{
    /**
     * create new state
     *
     * @param array $request
     * @return State
     */
    public function create(array $request, int $country_id): State
    {
        $state = new State();
        $state->country_id = $country_id;
        $state->title = $request['title'];
        $state->save();

        return $state;
    }

    /**
     * update State
     *
     * @param State $state
     * @param array $request
     * @return State
     */
    public function update(State $state, array $request): State
    {
        $state->title = $request['title'];
        $state->save();

        return $state;
    }

    /**
     * get all active countries
     *
     * @return Collection
     */
    public function getActiveStates(): Collection
    {
        return State::whereStatus(true)->orderBy('is_default', 'desc')->get();
    }

    /**
     * get all active countries
     *
     * @return Collection
     */
    public function getStateByCode($code): State
    {
        return State::whereCode($code)->first();
    }
}
