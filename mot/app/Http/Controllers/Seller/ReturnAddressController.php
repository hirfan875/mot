<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreAddress;
use App\Service\StoreAddressService;
use App\Service\CountryService;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;

class ReturnAddressController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    // show all records
    public function showForm()
    {
        $user = auth()->guard('seller')->user();
        $address = StoreAddress::whereStoreId($user->store_id)->first();
        $countryService = new CountryService();
        $countries = $countryService->getActiveCountries();
        $states = State::whereStatus(true)->where('country_id', 223)->orderBy('title', 'asc')->get();
        $cities = City::with(['state'])->whereHas('state', function($q) {
                            $q->where('country_id', '=', 223);
                     })->orderBy('title', 'asc')->get();
        
        if (!$address) {
            $storeAddress = new StoreAddress();
            $address = $storeAddress->emptyAddressColumns();
        }

        return view('seller.return-address.index', [
            'title' => __('Return Address'),
            'row' => $address,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities
        ]);
    }

    // form process
    public function formProcess(Request $request, StoreAddressService $storeAddressService)
    {
        $request->validate([
            'name' => 'required|max:75',
            'phone' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:50',
            'zipcode' => 'required|max:15',
            'country' => 'required|max:100',
        ]);

        $user = auth()->guard('seller')->user();
        $storeAddressService->updateOrCreate($user->store_id, $request->toArray());

        return back()->with('success', __('Address saved successfully.'));
    }
}
