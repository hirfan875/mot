<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Service\CustomerAddressService;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CustomersAddressesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:addresses-list|addresses-create|addresses-edit|addresses-delete', ['only' => ['index','store']]);
        $this->middleware('permission:addresses-create', ['only' => ['create','store']]);
        $this->middleware('permission:addresses-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:addresses-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function index(Customer $customer)
    {
        $records = CustomerAddress::with(['countries','cities','customer','states'])->whereCustomerId($customer->id)->latest()->get();

        return view('admin.customer-addresses.index', [
            'title' => __('Addresses'),
            'records' => $records,
            'customer' => $customer
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function create(Customer $customer)
    {
        $countries = Country::whereStatus(true)->orderBy('is_default', 'desc')->get();
        $states = State::whereStatus(true)->get();
        $cities = City::whereStatus(true)->get();

        return view('admin.customer-addresses.add', [
            'title' => __('Add Address'),
            'section_title' => __('Addresses'),
            'customer' => $customer,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @param CustomerAddress $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer, CustomerAddress $address)
    {
        $countries = Country::whereStatus(true)->orderBy('is_default', 'desc')->get();
        $states = State::whereStatus(true)->get();
        $cities = City::whereStatus(true)->get();

        return view('admin.customer-addresses.edit', [
            'title' => __('Edit Address'),
            'section_title' => __('Addresses'),
            'customer' => $customer,
            'row' => $address,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|max:75',
            'phone' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:50',
            'country' => 'required|max:100',
        ]);

        $customerAddressService = new CustomerAddressService();
        $customerAddressService->create($request->toArray(), $customer->id);

        return redirect()->route('admin.addresses', ['customer' => $customer->id])->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Customer $customer
     * @param CustomerAddress $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer, CustomerAddress $address)
    {
        $request->validate([
            'name' => 'required|max:75',
            'phone' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:50',
            'country' => 'required|max:100',
        ]);

        $customerAddressService = new CustomerAddressService();
        $customerAddressService->update($address, $request->toArray());

        return redirect()->route('admin.addresses', ['customer' => $customer->id])->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Customer $customer
     * @param CustomerAddress $address
     * @return \Illuminate\Http\Response
     */
    public function delete(Customer $customer, CustomerAddress $address)
    {
        $address->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * Set address as default
     *
     * @param Customer $customer
     * @param CustomerAddress $address
     * @return \Illuminate\Http\Response
     */
    public function makeDefault(Customer $customer, CustomerAddress $address)
    {
        $customerAddressService = new CustomerAddressService();
        $customerAddressService->makeDefault($address);

        return back()->with('success', __('Address set as default successfully.'));
    }
}
