<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreData;
use App\Service\StoreDataService;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Service\StoreService;
use App\Service\CountryService;
use App\Models\State;
use App\Models\City;
use App\Models\Country;

class StoreProfileController extends Controller
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

        $storeData = StoreData::with('store.store_profile_translate')->whereStoreId($user->store_id)->where('status',true)->latest()->first();
        $store = Store::with('store_profile_translate')->whereId($user->store_id)->latest()->first();
        return view('seller.store-profile.index', [
            'title' => __('Store Profile'),
            'row' => $storeData,
            'store' => $store
        ]);
    }

    // form process
    public function formProcess(Request $request, StoreDataService $storeDataService)
    {
        $request->validate([
            'data_changed' => 'required',
            'description' => 'required',
            'policies' => 'required',
        ]);

        if ($request->data_changed === 'yes') {
            $user = auth()->guard('seller')->user();
            $storeDataService->updateOrCreate($user->store_id, $request->toArray());
        }

        return back()->with('success', __('Profile updated successfully.'));
    }

    public function showEditStoreForm()
    {
        $user = auth()->guard('seller')->user();
        $storeData = StoreData::with('store.store_profile_translate')->whereStoreId($user->store_id)->where('status',true)->first();
        $store = Store::with('store_profile_translate')->whereId($user->store_id)->first();
        
        $countryService = new CountryService();
        $countries = Country::whereStatus(true)->where('id', 223)->get();
        $states = State::whereStatus(true)->where('country_id', 223)->orderBy('title', 'asc')->get();
        $cities = City::with(['state'])->whereHas('state', function($q) {
                            $q->where('country_id', '=', 223);
                     })->orderBy('title', 'asc')->get();

        return view('seller.store-profile.edit-seller-detail', [
            'title' => __('Edit Store'),
            'section_title' => __('Stores'),
            'row' => $store,
            'storeData' => $storeData,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function updateStoreDetails(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|max:50',
            'legal_name' => 'required|max:50',
            'phone' => 'required|max:20',
            'store_email' => 'required',
            'address' => 'required',
            'city' => 'required|max:50',
            'country' => 'required',
            'zipcode' => 'required|max:15',
            'tax_office' => 'required',
            'tax_id' => 'required_if:type,'.Store::PRIVATE_COMPANY.','.Store::LIMITED_STOCK_COMPANY,
            'tax_id_type' => 'required_if:type,'.Store::PRIVATE_COMPANY.','.Store::LIMITED_STOCK_COMPANY,
        ]);

        $StoreService = new StoreService();
        $StoreService->update($store, $request->toArray());

//        return redirect()->route('seller.dashboard')->with('success', __('Record updated successfully.'));
        return back()->with('success', __('Profile updated successfully.'));
    }
}
