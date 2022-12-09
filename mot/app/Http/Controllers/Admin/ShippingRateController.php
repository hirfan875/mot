<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use App\Service\ShippingRateService;

class ShippingRateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
//        $this->middleware('permission:shipping-rate-list|shipping-rate-create|shipping-rate-edit|shipping-rate-delete', ['only' => ['index','store']]);
//        $this->middleware('permission:shipping-rate-create', ['only' => ['create','store']]);
//        $this->middleware('permission:shipping-rate-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:shipping-rate-delete', ['only' => ['delete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Country $country)
    {
        $records = ShippingRate::where('country_id',$country->id)->orderBy('weight', 'asc')->get();
        return view('admin.shipping-rate.index', [
            'title' => __('Shipping Rate'),
            'records' => $records,
            'country' => $country
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Country $country)
    {
        return view('admin.shipping-rate.add', [
            'title' => __('Add Shipping Rate'),
            'section_title' => __('Shipping Rate'),
            'country' => $country
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Country $country)
    {
        $request->validate([
            'weight' => 'required|max:100',
            'rate' => 'required|max:100',
        ]);

        $shippingRateService = new ShippingRateService();
        $shippingRateService->create($request->toArray(), $country->id);
        

        return redirect()->route('admin.shipping.rates', ['country' => $country->id])->with('success', __('Record added successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShippingRate  $shippingRate
     * @return \Illuminate\Http\Response
     */
    public function show(ShippingRate $shippingRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShippingRate  $shippingRate
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country, ShippingRate $shippingRate)
    {
        return view('admin.shipping-rate.edit', [
            'title' => __('Edit Shipping Rate'),
            'section_title' => __('Shipping Rate'),
            'country' => $country,
            'row' => $shippingRate
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShippingRate  $shippingRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country, ShippingRate $shippingRate)
    {
        $request->validate([
            'weight' => 'required|max:100',
            'rate' => 'required|max:100',
        ]);

        $shippingRateService = new ShippingRateService();
        $shippingRateService->update($shippingRate, $request->toArray());

        return redirect()->route('admin.shipping.rates', ['country' => $country->id])->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShippingRate  $shippingRate
     * @return \Illuminate\Http\Response
     */
    public function delete(Country $country, ShippingRate $shippingRate)
    {
        $shippingRate->delete();
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
        $shippingRate = ShippingRate::findOrFail($request->id);
        $shippingRate->status = $request->value;
        $shippingRate->save();
    }

    /**
     * set selected country as default
     *
     * @param State $state
     * @return \Illuminate\Http\Response
     */
    public function setDefault(ShippingRate $shippingRate)
    {
        /** update all countries default status except selected */
        ShippingRate::where('id', '<>', $shippingRate->id)->update(['is_default' => null]);

        /** update selected country default flag */
        $shippingRate->is_default = 'Yes';
        $shippingRate->save();

        return back()->with('success', __('Record updated successfully.'));
    }
}
