<?php
namespace App\Http\Controllers\API;

use App\Http\Resources\CountryResource;
use App\Http\Resources\StateResource;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Http\Resources\Address as AddressResource;
use App\Models\CustomerAddress;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Auth;

class AddressController extends BaseController
{
    public function index()
    {
        try {
            $addresses = CustomerAddress::where('customer_id', Auth()->user()->id)->get();

        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }
        $success['addresses'] = AddressResource::collection($addresses);
        $success['customerInfo'] = Auth()->user();

        return $this->sendResponse($success, __('Data loaded successfully.'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation error', $validator->errors());
        }
        $input['customer_id'] = Auth::user()->id;
        $address = CustomerAddress::create($input);
        
        $customer = Auth::user();
        isset($input['phone']) ? $customer->phone = $input['phone'] : null;
        $customer->save();
        
        return $this->sendResponse(new AddressResource($address), __('Address has been created successfully'));
    }

    public function show($id)
    {
        $address = CustomerAddress::find($id);
        if (is_null($address)) {
            return $this->sendError('address does not exist.');
        }
        return $this->sendResponse(new AddressResource($address), 'Customer Address fetched.');
    }

    public function update(Request $request, CustomerAddress $address)
    {
        $input = $request->all();
//        $validator = Validator::make($input, [
//            'name' => 'required',
//            'phone' => 'required',
//            'address' => 'required',
//            'city' => 'required',
//            'state' => 'required',
//            'country' => 'required'
//        ]);
//
//        if($validator->fails()){
//            return $this->sendError($validator->errors());
//        }
        isset($input['name']) ? $address->name = $input['name'] : '';
        isset($input['email']) ? $address->email = $input['email'] : '';
        isset($input['phone']) ? $address->phone = $input['phone'] : '';
        isset($input['address']) ? $address->address = $input['address'] : '';
        isset($input['address2']) ? $address->address2 = $input['address2'] : '';
        isset($input['address3']) ? $address->address3 = $input['address3'] : '';
        isset($input['city']) ? $address->city = $input['city'] : '';
        isset($input['state']) ? $address->state = $input['state'] : '';
        isset($input['zipcode']) ? $address->zipcode = $input['zipcode'] : '';
        isset($input['country']) ? $address->country = $input['country'] : '';
        $address->save();
        
        $customer = Auth::user();
        isset($input['phone']) ? $customer->phone = $input['phone'] : null;
        $customer->save();
        return $this->sendResponse(new AddressResource($address), 'address updated.');
    }

    public function destroy(CustomerAddress $address)
    {
        $address->delete();
        return $this->sendResponse([], 'address deleted.');
    }

    public function getCountries(Request $request)
    {
        $countries = Country::whereStatus(true)->orderBy('is_default', 'desc')->get();

        if ($countries->count() == 0) {
            return $this->sendError(__('No country found'));
        }

        return $this->sendResponse(CountryResource::collection($countries), 'Data loaded successfully');
    }

    public function getStates(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $states = State::whereStatus(true)->where('country_id', $request->country_id)->get();

        if ($states->count() == 0) {
            return $this->sendError(__('No states found'));
        }

        return $this->sendResponse(StateResource::collection($states), 'Data loaded successfully');
    }

    public function getCities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $cities = City::whereStatus(true)->where('state_id', $request['state_id'])->get();

        if ($cities->count() == 0) {
            return $this->sendError(__('No cities found'));
        }

        return $this->sendResponse(StateResource::collection($cities), 'Data loaded successfully');
    }
}
