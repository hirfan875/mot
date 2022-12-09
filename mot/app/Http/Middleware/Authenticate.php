<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Arr;

class Authenticate extends Middleware
{
    protected $guards;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return mixed|string|null
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $logger = getLogger('authenticate');
        $logger->debug('Handling ', ['guards' => $guards, 'path' => $request->getPathInfo(), 'verb' => $request->getMethod()]);
        $this->guards = $guards;

        if (Arr::first($this->guards) === 'customer') {

            if (Auth()->guard('customer')->check()) {
                $logger->debug('Authenticated as customer', $request->toArray());
                return parent::handle($request, $next, ...$guards);
            }

            $logger->debug('Not authenticated as customer, when guard was customer', $request->toArray());
        }

        if (Arr::first($this->guards) === 'seller') {

            if (Auth()->guard('seller')->check()) {
                return parent::handle($request, $next, ...$guards);
            }

            $logger->debug('Not authenticated as seller, when guard was seller', $request->toArray());
        }

        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {

            if (Arr::first($this->guards) === 'customer') {
                return route('login-register');
            }

            if (Arr::first($this->guards) === 'seller') {
                return route('seller.login');
            }

            return route('admin.login');
        }
    }
}
