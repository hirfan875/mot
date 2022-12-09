<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Service\CityService;

class CitiesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:cities-list|cities-create|cities-edit|cities-delete', ['only' => ['index','store']]);
        $this->middleware('permission:cities-create', ['only' => ['create','store']]);
        $this->middleware('permission:cities-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:cities-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Country $country
     * @return \Illuminate\Http\Response
     */
    public function index(Country $country, State $state)
    {
        $records = City::where('state_id',$state->id)->orderBy('title', 'asc')->get();

        return view('admin.cities.index', [
            'title' => __('Cities'),
            'records' => $records,
            'country' => $country,
            'state' => $state
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Country $country
     * @return \Illuminate\Http\Response
     */
    public function create(Country $country, State $state)
    {
        return view('admin.cities.add', [
            'title' => __('Add City'),
            'section_title' => __('Cities'),
            'country' => $country,
            'state' => $state
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Country $country
     * @param City $city
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country, State $state, City $city)
    {
        return view('admin.cities.edit', [
            'title' => __('Edit City'),
            'section_title' => __('Cities'),
            'country' => $country,
            'state' => $state,
            'row' => $city
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Country $country
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Country $country, State $state)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $CityService = new CityService();
        $CityService->create($request->toArray(), $country->id, $state->id);

        return redirect()->route('admin.cities', ['country' => $country->id, 'state' => $state->id])->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Country $country
     * @param City $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country, State $state, City $city)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $CityService = new CityService();
        $CityService->update($city, $request->toArray());

        return redirect()->route('admin.cities', ['country' => $country->id, 'state' => $state->id])->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Country $country
     * @param City $city
     * @return \Illuminate\Http\Response
     */
    public function delete(Country $country, State $state, City $city)
    {
        $city->delete();
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
        $city = City::findOrFail($request->id);
        $city->status = $request->value;
        $city->save();
    }
}
