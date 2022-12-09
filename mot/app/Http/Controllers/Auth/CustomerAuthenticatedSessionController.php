<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CustomerLoginRequest;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Wishlist;
use App\Providers\RouteServiceProvider;
use App\Service\MoTCartService;
use App\Helpers\UtilityHelpers;
use App\Service\OrderService;
use Illuminate\Http\Request;
use App\Extensions\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Monolog\Logger;
use App\Models\Cart;
use function Sodium\increment;


class CustomerAuthenticatedSessionController extends Controller
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
        $logger = getLogger('customer-login');
//        $logger->debug('Authenticating  to ' , $request->toArray());
        $request->authenticate();

        $request->session()->regenerate();
        $viewSessionId = Session::get('previous-url');

        if (empty($viewSessionId)) {
            $this->redirectTo = url()->previous();
        }
        if ($viewSessionId == route('login-register')) {
            return redirect('/');
        }

        $customer = Auth::guard('customer')->user();
        if ($customer != null) {
            $customer_id = $customer->id;

            $customer->no_of_login = $customer->no_of_login + 1;
            $customer->save();
        }

        if(UtilityHelpers::getCartSessionId()) {
            $cartService = new MoTCartService(UtilityHelpers::getCartSessionId());
            $cartService->getAbandonedCart();
            $cartService->updateCartCustomer();
        } else {

            $getLastCart = '';
            $getLastCart = Cart::where('customer_id' , $customer_id)->where('status' , '!='  , Cart::TERMINATED_ID)->with('cart_products')->has('cart_products')->latest()->first();

            if ($getLastCart){
                Session::put('cart-session-id', $getLastCart->session_id);
            }
        }

        return redirect()->to($viewSessionId)->with('success', __('Successfully Login'));
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

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login-register');
    }
}
