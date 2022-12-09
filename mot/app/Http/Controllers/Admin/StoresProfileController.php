<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreData;
use App\Service\StoreDataService;
use Illuminate\Http\Request;
use App\Events\SellerReject;
use App\Events\SellerApproval;
use App\Service\StoreService;
use App\Service\CountryService;
use App\Models\StoreAddress;
use App\Service\StoreAddressService;
use App\Models\State;
use App\Models\City;


class StoresProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function index(Store $store)
    {
        $records = StoreData::adminList()->whereStoreId($store->id)->limit('1')->get();

        return view('admin.store-profile.index', [
            'title' => __('Store Profile'),
            'records' => $records,
            'store' => $store
        ]);
    }
    
    // show all records
    public function showForm(Store $store, StoreData $item)
    {
        $storeData = StoreData::with('store.store_profile_translate')->whereStoreId($store->id)->first();
        $store = Store::with('store_profile_translate')->whereId($store->id)->latest()->first();
        return view('admin.store-profile.add', [
            'title' => __('Store Profile'),
            'row' => $storeData,
            'store' => $store
        ]);
    }

    // form process
    public function formProcess(Request $request, Store $store, StoreDataService $storeDataService, StoreData $item)
    {
        $request->validate([
            'data_changed' => 'required',
            'description' => 'required',
            'policies' => 'required',
        ]);

        if ($request->data_changed === 'yes') {
            $user = auth()->guard('seller')->user();
            $storeDataService->updateOrCreate($store->id, $request->toArray());
        }

        return back()->with('success', __('Profile updated successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Store $store
     * @param StoreData $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store, StoreData $item)
    {
        return view('admin.store-profile.edit', [
            'title' => __('Edit Store Profile'),
            'section_title' => __('Stores'),
            'row' => $item,
            'store' => $store
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Store $store
     * @param StoreData $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store, StoreData $item)
    {
        $request->validate([
            'description' => 'required',
            'policies' => 'required',
        ]);

        $storeDataService = new StoreDataService();
        $storeDataService->update($item, $request->toArray());

        return redirect()->route('admin.stores.profile', ['store' => $store->id])->with('success', __('Record updated successfully.'));
    }
    
    // show all records
    public function showFormReturnAddress(Store $store)
    {
        $user = auth()->guard('seller')->user();
        $address = StoreAddress::whereStoreId($store->id)->first();
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

        return view('admin.store-profile.return-address', [
            'title' => __('Return Address'),
            'row' => $address,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'store'=>$store
        ]);
    }

    // form process
    public function storeReturnAddress(Request $request, Store $store, StoreAddressService $storeAddressService)
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
        $storeAddressService->updateOrCreate($store->id, $request->toArray());

        return back()->with('success', __('Address saved successfully.'));
    }

    /**
     * approve store
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function approve(Store $store, StoreData $item)
    {
        $banner = $item->banner;
        $logo = $item->logo;

        // if store don't change his banner & logo then copy these from previous data
        if ($store->store_data) {
            if (!$item->banner) {
                $banner = $store->store_data->banner;
            }

            if (!$item->logo) {
                $logo = $store->store_data->logo;
            }
        }

        $item->status = StoreData::STATUS_APPROVED;
        $item->banner = $banner;
        $item->logo = $logo;
        $item->save();
        
        // sending email
        event(new SellerApproval($store));

        return back()->with('success', __('Store profile approved successfully.'));
    }

    /**
     * reject store
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function reject(Store $store, StoreData $item)
    {
        $item->status = StoreData::STATUS_REJECTED;
        $item->save();
        
        // sending email
        event(new SellerReject($store));

        return back()->with('success', __('Store profile rejected successfully.'));
    }
}
