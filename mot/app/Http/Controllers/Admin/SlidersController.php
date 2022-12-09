<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Service\SliderService;
use App\Service\Media;

class SlidersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:sliders-list|sliders-create|sliders-edit|sliders-delete', ['only' => ['index','store']]);
        $this->middleware('permission:sliders-create', ['only' => ['create','store']]);
        $this->middleware('permission:sliders-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:sliders-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Slider::orderBy('sort_order', 'asc')->get();

        return view('admin.sliders.index', [
            'title' => __('Sliders'),
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
        return view('admin.sliders.add', [
            'title' => __('Add Slider'),
            'section_title' => __('Sliders')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Slider $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', [
            'title' => __('Edit Slider'),
            'section_title' => __('Sliders'),
            'row' => $slider
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
//            'image[]' => 'required|mimes:jpg,jpeg,png'
        ]);

        $SliderService = new SliderService();
        $SliderService->create($request->toArray());

        return redirect()->route('admin.sliders')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Slider $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
//            'image[]' => 'sometimes|mimes:jpg,jpeg,png'
        ]);

        $SliderService = new SliderService();
        $SliderService->update($slider, $request->toArray());

        return redirect()->route('admin.sliders')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Slider $slider
     * @return \Illuminate\Http\Response
     */
    public function delete(Slider $slider)
    {
        Media::delete($slider->image);
        $slider->delete();

        // decrement sort order
        Slider::where('sort_order', '>', $slider->sort_order)->decrement('sort_order');

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
        $brand = Slider::findOrFail($request->id);
        $brand->status = $request->value;
        $brand->save();
    }

    /**
     * Show list for changing sort order
     *
     * @return \Illuminate\Http\Response
     */
    public function sorting()
    {
        $sliders = Slider::orderBy('sort_order', 'asc')->get();

        return view('admin.sliders.sorting', [
            'title' => __('Sorting'),
            'section_title' => __('Sliders'),
            'sliders' => $sliders
        ]);
    }

    /**
     * Update sorting order
     *
     * @param Request $request
     * @return void
     */
    public function updateSorting(Request $request)
    {
        foreach ( $request['items'] as $k=>$r ) {
            Slider::where('id', $r['id'])->update(['sort_order' => $r['order']]);
        }
    }
}
