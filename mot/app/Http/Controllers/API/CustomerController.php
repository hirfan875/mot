<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Http\Resources\Customer as CustomerResource;
use App\Models\Customer;

class CustomerController extends BaseController
{

    public function index()
    {
        try {
            $customer = Customer::with(['addresses'])->where('id',Auth()->user()->id)->get();

        } catch (\Exception $exc) {
           return $this->sendError(__('Error'), __($exc->getMessage()));
        }
        $success['customer'] =  CustomerResource::collection($customer);

        return $this->sendResponse($success, __('Customer'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        $customer = Customer::create($input);

        return $this->sendResponse(new CustomerResource($customer), 'Customer created.');
    }

    public function show($id)
    {
        $customer = Customer::find($id);

        if (is_null($customer)) {
            return $this->sendError('Customer does not exist.');
        }

        return $this->sendResponse(new CustomerResource($customer), 'Customer Address fetched.');
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $customer = auth()->user();
        if ($customer == null) {
            return $this->sendError(__('Customer does not exist.'));
        }
        if (isset($input['image'])) {
            $name = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->store('images');
            $customer->image = $path;
        }
        isset($input['name']) ? $customer->name = $input['name'] : null;
        isset($input['email']) ? $customer->email = $input['email'] : null;
        isset($input['phone']) ? $customer->phone = $input['phone'] : null;
//        isset($input['is_guest']) ? $customer->is_guest = $input['is_guest'] : null;
        isset($input['birthday']) ? $customer->birthday = \Carbon\Carbon::parse($input['birthday'])->format('Y-m-d') : null;
//        isset($input['password']) ? $customer->password = $input['password'] : null;
        $customer->save();

        return $this->sendResponse(new CustomerResource($customer), 'Customer has been updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return $this->sendResponse([], 'Customer has been deleted.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkGuestAccount(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $customer = Customer::whereStatus(true)->where('email', $request->email)->first();

        if ($customer) {
            return $this->sendResponse($customer, 'Customer already exist.');
        }
        return $this->sendError('Customer not exist.');
    }
}
