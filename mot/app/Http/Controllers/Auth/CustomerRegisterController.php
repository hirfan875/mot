<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Extensions\Response;
use App\Service\CustomerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

class CustomerRegisterController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $key = 'previous-url';
        $viewSessionId = URL::previous();
        Session::put($key, $viewSessionId);
        if (empty($viewSessionId)) {
            $this->redirectTo = url()->previous();
        }
        return view('web.auth.login-register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'register_name' => 'required|string|max:255',
            'register_email' => 'required|string|email|max:255|unique:customers,email',
            'register_password' => 'required|string|confirmed|min:8',
            'register_password_confirmation' => 'required',
        ]);

        $customer = Customer::create([
            'name' => $request->register_name,
            'email' => $request->register_email,
            'password' => Hash::make($request->register_password),
        ]);
        Auth::guard('customer')->login($customer);
        
        
        addCustomerGetResponse($request->register_name,$request->register_email);
        
//        event(new Registered($customer));
        $customerinfo = new CustomerService();
        $customerinfo->sendVerifyMessage($customer,$request->toArray());
        return Response::redirect(RouteServiceProvider::MYACCOUNT, $request,['success'=> __('Customer has been registered successfully.')]);
    }
}
