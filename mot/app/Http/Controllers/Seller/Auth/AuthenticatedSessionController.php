<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SellerLoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;
use App\Models\StoreStaff;
use Illuminate\Support\Str;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('seller.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\SellerLoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SellerLoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect(RouteServiceProvider::SELLERHOME);
    }
    
    public function storeAdmin(Request $request,$id)
    {
        
        $storeStaff = StoreStaff::where('id', $id)->first();
                    
        if (Auth::guard('seller')->loginUsingId($id)) {
//           Str::lower($this->input('email')) . '|' . $this->ip();
        }

        return redirect(RouteServiceProvider::SELLERHOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('seller')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/seller');
    }
}
