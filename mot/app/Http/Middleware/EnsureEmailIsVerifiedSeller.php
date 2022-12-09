<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerifiedSeller extends Middleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return \Illuminate\Http\RedirectResponse|Response|mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                ! $request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                ? new Response(['success'=>true , 'message'=>__('Your email address is not verified.')], 403)
                : Redirect::guest(URL::route('seller.verification.notice'));
        }

        return $next($request);
    }
}
