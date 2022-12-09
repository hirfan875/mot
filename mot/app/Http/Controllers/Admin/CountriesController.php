<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Service\CountryService;

class CountriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:countries-list|countries-create|countries-edit|countries-delete', ['only' => ['index','store']]);
        $this->middleware('permission:countries-create', ['only' => ['create','store']]);
        $this->middleware('permission:countries-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:countries-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Country::orderBy('status', 'desc')->paginate(25);

        return view('admin.countries.index', [
            'title' => __('Countries'),
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
        return view('admin.countries.add', [
            'title' => __('Add Country'),
            'section_title' => __('Countries')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Country $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        return view('admin.countries.edit', [
            'title' => __('Edit Country'),
            'section_title' => __('Countries'),
            'row' => $country
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
            'title' => 'required|max:100',
            'code' => 'required|max:10|unique:countries',
        ]);

        $CountryService = new CountryService();
        $CountryService->create($request->toArray());

        return redirect()->route('admin.countries')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Country $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $request->validate([
            'title' => 'required|max:100',
            'code' => 'required|max:10|unique:countries,code,'.$country->id,
        ]);

        $CountryService = new CountryService();
        $CountryService->update($country, $request->toArray());

        return redirect()->route('admin.countries')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Country $country
     * @return \Illuminate\Http\Response
     */
    public function delete(Country $country)
    {
        $country->delete();
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
        $country = Country::findOrFail($request->id);
        $country->status = $request->value;
        $country->save();
    }

    /**
     * set selected country as default
     *
     * @param Country $country
     * @return \Illuminate\Http\Response
     */
    public function setDefault(Country $country)
    {
        /** update all countries default status except selected */
        Country::where('id', '<>', $country->id)->update(['is_default' => null]);

        /** update selected country default flag */
        $country->is_default = 'Yes';
        $country->save();

        return back()->with('success', __('Record updated successfully.'));
    }
}
