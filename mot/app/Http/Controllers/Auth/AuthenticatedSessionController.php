<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CustomerLoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Service\MoTCartService;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('web.auth.login-register');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\CustomerLoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CustomerLoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect(RouteServiceProvider::MYACCOUNT);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('customer')->logout();

//        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
