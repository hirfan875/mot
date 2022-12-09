<?php

namespace App\Http\Controllers\Admin;

use App\Events\StoreStatusUpdate;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreData;
use App\Models\Product;
use App\Models\StoreStaff;
use App\Models\Brand;
use App\Models\StoreAddress;
use App\Service\CountryService;
use App\Service\ProductService;
use App\Models\ProductAttribute;
use App\Service\StoreAddressService;
use App\Service\StoreService;
use Illuminate\Http\Request;
use App\Events\SellerApproval;
use App\Events\SetupIyzicoSubMerchant;
use App\Models\State;
use App\Models\City;
use App\Events\SellerReject;


class StoresController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:stores-list|stores-create|stores-edit|stores-delete', ['only' => ['index','store']]);
        $this->middleware('permission:stores-create', ['only' => ['create','store']]);
        $this->middleware('permission:stores-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:stores-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Store::approved()->with('country')->latest()->get();

        return view('admin.stores.index', [
            'title' => __('Stores'),
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countryService = new CountryService();
        $countries = $countryService->getActiveCountries();
        $states = State::whereStatus(true)->where('country_id', 223)->orderBy('title', 'asc')->get();
        $cities = City::with(['state'])->whereHas('state', function($q) {
                            $q->where('country_id', '=', 223);
                     })->orderBy('title', 'asc')->get();

        return view('admin.stores.add', [
            'title' => __('Add Store'),
            'section_title' => __('Stores'),
            'countries' => $countries,
            'cities' => $cities,
            'states' => $states
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $countryService = new CountryService();
        $countries = $countryService->getActiveCountries();
        $states = State::whereStatus(true)->where('country_id', 223)->orderBy('title', 'asc')->get();
        $cities = City::with(['state'])->whereHas('state', function($q) {
                            $q->where('country_id', '=', 223);
                     })->orderBy('title', 'asc')->get();

        return view('admin.stores.edit', [
            'title' => __('Edit Store'),
            'section_title' => __('Stores'),
            'row' => $store,
            'countries' => $countries,
            'cities' => $cities,
            'states' => $states
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'legal_name' => 'required|max:50',
            'phone' => 'required|max:20',
            'address' => 'required',
            'type'  => 'required',
            'city' => 'required|max:50',
            'country' => 'required',
            'zipcode' => 'required|max:15',
            'email' => 'required|max:100|email|unique:store_staff',
            'password' => 'required|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            'tax_office' => 'required',
            'tax_id' => 'required_if:type,'.Store::PRIVATE_COMPANY.','.Store::LIMITED_STOCK_COMPANY,
        ]);

        $StoreService = new StoreService();
        $StoreService->create($request->toArray());

        return redirect()->route('admin.stores')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|max:50',
            'legal_name' => 'required|max:50',
            'phone' => 'required|max:20',
            'store_email' => 'required',
            'type'  => 'required',
            'address' => 'required',
            'city' => 'required|max:50',
            'country' => 'required',
            'zipcode' => 'required|max:15',
//            'tax_office' => 'required',
//            'tax_id' => 'required_if:type,'.Store::PRIVATE_COMPANY.','.Store::LIMITED_STOCK_COMPANY,
        ]);

        $StoreService = new StoreService();
        $StoreService->update($store, $request->toArray());

        return redirect()->route('admin.stores')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function delete(Store $store)
    {
        $staff = StoreStaff::where('store_id', $store->id)->delete();
        $storeData = StoreData::where('store_id', $store->id)->delete();
        $storeAddress = StoreAddress::where('store_id', $store->id)->delete();
          $product = Product::where('store_id', $store->id)->get();
            foreach ($product  as $row){
                $productService = new ProductService();
                $productService->forceDelete($row);
            }
          $brand = Brand::where('store_id', $store->id)->delete();
        $store->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * update status
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request)
    {
        $store = Store::findOrFail($request->id);
        $store->status = $request->value;
        $store->save();

        StoreStatusUpdate::dispatch($store);
    }

    /**
     * approve store
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function approve(Store $store)
    {
        if (!$store->store_data) {
            return back()->with('error', __('Store profile incomplete.'));
        }

//        if (!$store->store_data->banner || !$store->store_data->logo) {
//            return back()->with('error', __('Store banner/logo missing.'));
//        }

//        if(!$store->hasSubMerchantKey()) {
//            return back()->with('error', __('Firstly generate submerchant key on iyzico then approve this store.'));
//        }

        $store->is_approved = Store::STATUS_APPROVED;
        $store->save();

        // sending email
        event(new SellerApproval($store));
        return back()->with('success', __('Store approved successfully.'));
    }

    /**
     * reject store
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function reject(Store $store)
    {
        $store->is_approved = Store::STATUS_REJECTED;
        $store->save();

        event(new SellerReject($store));

        return back()->with('success', __('Store rejected successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function returnAddress(Store $store)
    {
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

        return view('admin.stores.return-address', [
            'title' => __('Return Address'),
            'row' => $address,
            'store' => $store,
            'countries' => $countries,
            'cities' => $cities,
            'states' => $states
        ]);
    }

    /**
     * update store return address
     *
     * @param Store $store
     * @param Request $request
     * @param StoreAddressService $storeAddressService
     * @return \Illuminate\Http\Response
     */
    public function returnAddressUpdate(Store $store, Request $request, StoreAddressService $storeAddressService)
    {
        $request->validate([
            'name' => 'required|max:75',
            'phone' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:50',
            'zipcode' => 'required|max:15',
            'country' => 'required|max:100',
        ]);

        $storeAddressService->updateOrCreate($store->id, $request->toArray());

        return back()->with('success', __('Address saved successfully.'));
    }

    /**
     * create submerchant store
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function requestSubmerchantOnIyzico(Store $store)
    {
        SetupIyzicoSubMerchant::dispatch($store);
        return back()->with('success', __('Request has been submitted check back after few minutes.'));
    }
}
