<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Service\PageService;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:pages-list|pages-create|pages-edit|pages-delete', ['only' => ['index','store']]);
        $this->middleware('permission:pages-create', ['only' => ['create','store']]);
        $this->middleware('permission:pages-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:pages-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Page::orderBy('title', 'asc')->get();

        return view('admin.pages.index', [
            'title' => __('Pages'),
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
        return view('admin.pages.add', [
            'title' => __('Add Page'),
            'section_title' => __('Pages')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Page $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', [
            'title' => __('Edit Page'),
            'section_title' => __('Pages'),
            'row' => $page
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
            'title' => 'required',
        ]);

        $pageService = new PageService();
        $pageService->create($request->toArray());

        return redirect()->route('admin.pages')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Page $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $pageService = new PageService();
        $pageService->update($page, $request->toArray());

        return redirect()->route('admin.pages')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Page $page
     * @return \Illuminate\Http\Response
     */
    public function delete(Page $page)
    {
        $page->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }
}
