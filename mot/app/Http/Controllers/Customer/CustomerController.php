<?php

namespace App\Http\Controllers\Customer;

use App\Extensions\Response;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Service\CustomerAddressService;
use App\Service\CustomerService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Logger;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CustomerController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function changePassword(Request $request)
    {
        $customer = Customer::findOrFail(Auth()->user()->id);
        $countries = Country::whereStatus(true)->get();
        $states = State::whereStatus(true)->get();
        $cities = City::whereStatus(true)->get();

        return Response::success('customer.change-password', ['states' => $states, 'cities' => $cities, 'customer' => $customer, 'countries' => $countries], $request);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $logger = getLogger('add-address', Logger::DEBUG, 'logs/address.log');
        try {
//            $logger->debug('Adding Address');
            $validator = \Validator::make($request->all(), [
                'name' => 'required|max:200',
                'phone' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
            ]);

            if ($validator->fails()) {
//                $logger->debug('Adding Address Failed.' , [$validator->errors()]);
                return redirect(route('list-address'))
                    ->with('message', __('Unable to add address.'))
                    ->withErrors($validator)
                    ->withInput();
            }

            $customerAddressService = new CustomerAddressService();
            $address = $customerAddressService->create($request->toArray(), Auth()->user()->id);

        } catch (\Exception $exc) {
            //
            // web.auth.account
            // ... We cant Do it like this .. These pages need to be separated
            return Response::error('customer.address', __('Unable to add address.'), $exc, $request, 400);
        }
        return Response::redirect(route('list-address'))->with('message', __('Address saved successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $getCustomerAddress = CustomerAddress::whereId($id )->where('customer_id',Auth()->user()->id )->first();
        return redirect()->route('my-account')->with('addressdata', $getCustomerAddress);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|max:200',
                'phone' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
            ]);

            $address = CustomerAddress::whereId($id)->where('customer_id',Auth()->user()->id )->first();

            $customeraddress = new CustomerAddressService();
            $customeraddress->update( $address ,$request->toArray());

        } catch (\Exception $exc) {
            return Response::error('customer.address', __('Unable to add address. '). __($exc->getMessage()), [$exc->getMessage()], $request, 400);
        }
        return Response::success(null, ['message'=> __('Your request has been submitted successfully')], $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        CustomerAddress::find($request->addressid)->delete();
        return Response::success(null, ['message'=> __('Address delete successfully.')], $request);

    }
    public function updateCustomerInfo(Request $request)
    {
        $customer = Customer::findOrFail(Auth::user()->id);
        $customerService = new CustomerService();
        $customerService->update($customer ,$request->toArray());
        return Response::redirect(route('my-account'),$request, ['message'=> __('Record edit successfully.')] );

    }
    public function customerChangePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{6,}$/|confirmed',
            'password_confirmation' => 'required',
        ]);

        $customer = Customer::whereId(Auth::user()->id)->first();
        $customerinfo = new CustomerService();
        $customerinfo->updatePassword($customer ,$request->toArray());
        $customerinfo->sendChangePasswordMessage($customer,$request->toArray());

        return redirect()->route('change-password')->with('success', __('Password Update successfully.'));
    }

    public function getStates(Request $request)
    {
        $states = State::whereStatus(true)->where('country_id',$request['country'])->get();
        return response()->json(['states' => $states, 'success' => true]);
    }

    public function getCities(Request $request)
    {
        $cities = City::whereStatus(true)->where('state_id',$request['state'])->get();
        return response()->json(['cities' => $cities, 'success' => true]);
    }

    public function checkGuestAccount(Request $request)
    {
        $customer = Customer::whereStatus(true)->where('email',$request['email'])->first();

        if($customer){
            return response()->json(['customer' => $customer, 'success' => true]);
        }
        return response()->json(['customer' => $customer, 'success' => false]);
    }

     public function avatar()
    {
        return view('image-upload');
    }

    public function storeAvatar(Request $request)
    {
        $validatedData = $request->validate([
         'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);

        $name = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->store('images');

        $save = Customer::whereId(Auth::user()->id)->first();
        $save->image = $path;
        $save->save();
        return redirect()->back()->with('status', 'Image Has been uploaded successfully in laravel 8');

    }
}
