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

class AddressController extends Controller
{
    public function index(Request  $request)
    {
        try {
            $addresses = CustomerAddress::where('customer_id',Auth()->user()->id)->get();
            $customer = Customer::findOrFail(Auth()->user()->id);
            $countries = Country::whereStatus(true)->orderBy('is_default', 'desc')->get();
            $states = State::whereStatus(true)->get();
            $cities = City::whereStatus(true)->get();

        } catch (\Exception $exc) {
            // string $view, string $message,$error, Request $request = null, $error_code = 422
            return Response::error('customer.address', __($exc->getMessage()), $exc, $request);
        }
        return Response::success('customer.address', [
            'addresses' => $addresses,
            'customerInfo' => $customer,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities
        ], $request);
    }

    public function getById($id)
    {
        $address = CustomerAddress::find($id);

        $data = [
            'address' => $address
        ];

        return response()->json($data);
    }
}
