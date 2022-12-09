<?php

namespace App\Http\Controllers\Customer;

use App\Extensions\Response;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Wishlist;
use App\Service\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class MyAccountController extends Controller
{
    public function show(Request $request)
    {

        try {
            $addresses = CustomerAddress::where('customer_id',Auth()->user()->id)->get();
            $wishlists = Wishlist::with('product')->where('customer_id',Auth()->user()->id)->get();
            $customer = Customer::findOrFail(Auth()->user()->id);
            $orderService = new OrderService();
            $countries = Country::whereStatus(true)->get();
            $states = State::whereStatus(true)->get();
            $cities = City::whereStatus(true)->get();

        } catch (\Exception $exc) {
            return Response::error('customer.account', __($exc->getMessage()), $exc, $request);
        }
        return Response::success('customer.account', [
            'customer' => $customer,
            'addresses' => $addresses,
            'wishlists' => $wishlists,
            'orderService' => $orderService,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities
        ], $request);
    }
}
