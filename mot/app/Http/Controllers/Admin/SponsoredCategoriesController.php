<?php

namespace App\Http\Controllers\Admin;

use App\Events\SponsorSectionStatusUpdate;
use App\Http\Controllers\Controller;
use App\Models\SponsorSection;
use App\Service\SponsorSectionService;
use Illuminate\Http\Request;

class SponsoredCategoriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
         $this->middleware('permission:sponsored-categories-list|sponsored-categories-create|sponsored-categories-edit|sponsored-categories-delete', ['only' => ['index','store']]);
        $this->middleware('permission:sponsored-categories-create', ['only' => ['create','store']]);
        $this->middleware('permission:sponsored-categories-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:sponsored-categories-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = SponsorSection::latest()->get();

        return view('admin.sponsored-categories.index', [
            'title' => __('Sponsored Categories'),
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
        return view('admin.sponsored-categories.add', [
            'title' => __('Add Section'),
            'section_title' => __('Sponsored Categories')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param SponsorSection $item
     * @return \Illuminate\Http\Response
     */
    public function edit(SponsorSection $item)
    {
        $item->load(['categories']);

        return view('admin.sponsored-categories.edit', [
            'title' => __('Edit Section'),
            'section_title' => __('Sponsored Categories'),
            'row' => $item
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
//        $request->validate([
//            'title' => 'required',
//            'categories' => 'required|array|min:3|max:3',
//            'categories.*.image.*' => 'required|mimes:jpg,jpeg,png'
//        ], [
//            'categories.*.image.*.required' => __('The image field is required')
//        ]);

        $sponsorSectionService = new SponsorSectionService();

        $sponsorSectionService->create($request->toArray());

        return redirect()->route('admin.sponsored.categories')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SponsorSection $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SponsorSection $item)
    {
        $request->validate([
            'title' => 'required',
//            'categories' => 'required|array|min:3|max:3',
        ]);

        $sponsorSectionService = new SponsorSectionService();
        $sponsorSectionService->update($item, $request->toArray());

        return redirect()->route('admin.sponsored.categories')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SponsorSection $item
     * @return \Illuminate\Http\Response
     */
    public function delete(SponsorSection $item)
    {
        $sponsorSectionService = new SponsorSectionService();
        $sponsorSectionService->delete($item);

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
        $section = SponsorSection::findOrFail($request->id);
        $section->status = $request->value;
        $section->save();

        SponsorSectionStatusUpdate::dispatch($section);
    }
}
