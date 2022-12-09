<?php

namespace App\Extensions;

use App\Models\Store;
use App\Models\StoreStaff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class SellerUserProvider implements UserProvider
{
    /**
     * The Seller User Model
     */
    private $model;

    /**
     * Create a new customer user provider.
     *
     * CustomerUserProvider constructor.
     * @param StoreStaff $staff
     */
    public function __construct(StoreStaff $staff)
    {
        $this->model = $staff;
        $logger = getLogger('SellerUserProvider');
        $logger->info('Constructed');
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return null;
        }

        $seller = $this->model->query()->where('email', $credentials['email'])->whereStatus(true)->whereHas('store', function ($query) {
            $query->whereStatus(true)->whereIsApproved(Store::STATUS_APPROVED);
        })->first();

        return $seller;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $seller
     * @param array $credentials Request credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $seller, array $credentials)
    {
        return ($credentials['email'] == $seller->getAuthIdentifier() &&
            Hash::check($credentials['password'], $seller->getAuthPassword()));
    }

    public function retrieveById($identifier)
    {
        return $this->model->query()->whereEmail($identifier)->first();
    }

    public function retrieveByToken($identifier, $token)
    {
        $seller = $this->model->query()->whereEmail($identifier)->first();

        return $seller && $seller->getRememberToken() && hash_equals($seller->getRememberToken(), $token)
            ? $seller : null;
    }

    public function updateRememberToken(Authenticatable $seller, $token)
    {
        $this->model->query()
            ->where($seller->getAuthIdentifierName(), $seller->getAuthIdentifier())
            ->update([$seller->getRememberTokenName() => $token]);
    }
}
