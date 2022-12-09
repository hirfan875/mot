<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HelpCenter;
use App\Service\HelpCenterService;

class HelpCenterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        /*$this->middleware('permission:pages-list|pages-create|pages-edit|pages-delete', ['only' => ['index','store']]);
        $this->middleware('permission:pages-create', ['only' => ['create','store']]);
        $this->middleware('permission:pages-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:pages-delete', ['only' => ['delete']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = HelpCenter::orderBy('title', 'asc')->get();

        return view('admin.help-centers.index', [
            'title' => __('Help Centers'),
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
        return view('admin.help-centers.add', [
            'title' => __('Add'),
            'section_title' => __('Help Centers')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HelpCenter $helpCenter
     * @return \Illuminate\Http\Response
     */
    public function edit(HelpCenter $helpCenter)
    {
        return view('admin.help-centers.edit', [
            'title' => __('Edit Help Center'),
            'section_title' => __('Help Centers'),
            'row' => $helpCenter
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
            'title' => 'required'
        ]);

        $helpCenterService = new HelpCenterService();
        $helpCenterService->create($request->toArray());

        return redirect()->route('admin.help-centers')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param HelpCenter $helpCenter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HelpCenter $helpCenter)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $helpCenterService = new HelpCenterService();
        $helpCenterService->update($helpCenter, $request->toArray());

        return redirect()->route('admin.help-centers')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HelpCenter $helpCenter
     * @return \Illuminate\Http\Response
     */
    public function delete(HelpCenter $helpCenter)
    {
        $helpCenter->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }
}
