<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Service\StateService;

class StatesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:states-list|states-create|states-edit|states-delete', ['only' => ['index','store']]);
        $this->middleware('permission:states-create', ['only' => ['create','store']]);
        $this->middleware('permission:states-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:states-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Country $country
     * @return \Illuminate\Http\Response
     */
    public function index(Country $country)
    {
        $records = State::where('country_id',$country->id)->orderBy('title', 'asc')->get();

        return view('admin.states.index', [
            'title' => __('States'),
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
        return view('admin.states.add', [
            'title' => __('Add State'),
            'section_title' => __('States'),
            'country' => $country
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param State $state
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country, State $state)
    {
        return view('admin.states.edit', [
            'title' => __('Edit State'),
            'section_title' => __('States'),
            'country' => $country,
            'row' => $state
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Country $country)
    {
        $request->validate([
            'title' => 'required|max:100',
        ]);

        $StateService = new StateService();
        $StateService->create($request->toArray(), $country->id);
        

        return redirect()->route('admin.states', ['country' => $country->id])->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param State $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country, State $state)
    {
        $request->validate([
            'title' => 'required|max:100',
        ]);

        $StateService = new StateService();
        $StateService->update($state, $request->toArray());

        return redirect()->route('admin.states', ['country' => $country->id])->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param State $state
     * @return \Illuminate\Http\Response
     */
    public function delete(Country $country, State $state)
    {
        $state->delete();
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
        $state = State::findOrFail($request->id);
        $state->status = $request->value;
        $state->save();
    }

    /**
     * set selected country as default
     *
     * @param State $state
     * @return \Illuminate\Http\Response
     */
    public function setDefault(State $state)
    {
        /** update all countries default status except selected */
        State::where('id', '<>', $state->id)->update(['is_default' => null]);

        /** update selected country default flag */
        $state->is_default = 'Yes';
        $state->save();

        return back()->with('success', __('Record updated successfully.'));
    }
}
