<?php

namespace App\Extensions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Customer;
use Monolog\Logger;

class CustomerUserProvider implements UserProvider
{
    /**
     * The Customer User Model
     */
    private $model;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Create a new customer user provider.
     *
     * CustomerUserProvider constructor.
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->model = $customer;
        $this->logger = getLogger('CustomerUserProvider');
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $this->logger->debug('retrieveByCredentials');
        if (empty($credentials)) {
            return;
        }

        $customer = $this->model->query()->where('email', $credentials['email'])->whereStatus(true)->first();
        return $customer;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $customer
     * @param array $credentials Request credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $customer, array $credentials)
    {
        $this->logger->debug('validateCredentials');
        return ($credentials['email'] == $customer->getAuthIdentifier() &&
            Hash::check($credentials['password'], $customer->getAuthPassword()));
    }

    public function retrieveById($identifier)
    {
        $this->logger->debug('retrieveById', [$identifier]);
        return $this->model->whereEmail($identifier)->first();
    }

    public function retrieveByToken($identifier, $token)
    {
        $this->logger->debug('retrieveByToken');
        $customer = $this->model->whereEmail($identifier)->first();

        return $customer && $customer->getRememberToken() && hash_equals($customer->getRememberToken(), $token)
            ? $customer : null;
    }

    public function updateRememberToken(Authenticatable $customer, $token)
    {
        $this->logger->debug('updateRememberToken');
        $this->model->query()
            ->where('email', $customer->email)
            ->update([$customer->getRememberTokenName() => $token]);
    }
}
