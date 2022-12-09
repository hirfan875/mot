<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Extensions\CustomerUserProvider;
use App\Extensions\SellerUserProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // add custom guard provider
        Auth::provider('seller', function ($app, array $config) {
            return new SellerUserProvider($app->make('App\Models\StoreStaff'));
        });
        // add custom guard provider
        Auth::provider('customer', function ($app, array $config) {
            return new CustomerUserProvider($app->make('App\Models\Customer'));
        });

        //
    }
}
