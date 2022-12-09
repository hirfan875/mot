<?php

namespace App\Http\Controllers\Admin;

use App\Events\BannerStatusUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Service\BannerService;

class BannersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:banners-list|banners-create|banners-edit|banners-delete', ['only' => ['index','store']]);
        $this->middleware('permission:banners-create', ['only' => ['create','store']]);
        $this->middleware('permission:banners-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:banners-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Banner::latest()->get();

        return view('admin.banners.index', [
            'title' => __('Banners'),
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
        return view('admin.banners.add', [
            'title' => __('Add Banner'),
            'section_title' => __('Banners')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Banner $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', [
            'title' => __('Edit Banner'),
            'section_title' => __('Banners'),
            'row' => $banner
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
//            'image' => 'required|mimes:jpg,jpeg,png',
//            'image_mobile' => 'required|mimes:jpg,jpeg,png'
        ]);

        $bannerService = new BannerService();
        $bannerService->create($request->toArray());

        return redirect()->route('admin.banners')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Banner $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
//            'image' => 'sometimes|mimes:jpg,jpeg,png',
//            'image_mobile' => 'required|mimes:jpg,jpeg,png'
        ]);

        $bannerService = new BannerService();
        $bannerService->update($banner, $request->toArray());

        return redirect()->route('admin.banners')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Banner $banner
     * @return \Illuminate\Http\Response
     */
    public function delete(Banner $banner)
    {
        $bannerService = new BannerService();
        $bannerService->delete($banner);

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
        $banner = Banner::findOrFail($request->id);
        $banner->status = $request->value;
        $banner->save();

        BannerStatusUpdate::dispatch($banner);
    }
}
