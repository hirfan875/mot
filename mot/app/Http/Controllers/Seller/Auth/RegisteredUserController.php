<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Models\StoreStaff;
use App\Providers\RouteServiceProvider;
//use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\SellerRegistered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('seller.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:vendors',
            'password' => 'required|string|confirmed|min:8',
        ]);
        /** @var StoreStaff $storeStaff */
        $storeStaff = StoreStaff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Auth::guard('seller')->login($storeStaff);
        event(new SellerRegistered($storeStaff));

        return redirect(RouteServiceProvider::SELLERHOME);
    }
}
