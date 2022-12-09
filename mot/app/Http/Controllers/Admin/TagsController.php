<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Service\FilterTagsService;
use App\Service\TagService;

class TagsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:tags-list|tags-create|tags-edit|tags-delete', ['only' => ['index','store']]);
        $this->middleware('permission:tags-create', ['only' => ['create','store']]);
        $this->middleware('permission:tags-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:tags-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filterTagsService = new FilterTagsService();
        $records = $filterTagsService->latest()->get();

        return view('admin.tags.index', [
            'title' => __('Tags'),
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
        return view('admin.tags.add', [
            'title' => __('Add Tag'),
            'section_title' => __('Tags')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', [
            'title' => __('Edit Tag'),
            'section_title' => __('Tags'),
            'row' => $tag
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

        $tagService = new TagService();
        $tagService->create($request->toArray());

        return redirect()->route('admin.tags')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $tagService = new TagService();
        $tagService->update($tag, $request->toArray());

        return redirect()->route('admin.tags')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function delete(Tag $tag)
    {
        try {
            $tag->delete();
            return back()->with('success', __('Record deleted successfully.'));
        } catch (\Throwable $e) {
            return back()->with('error', __('You cannot delete this record because record linked with other records.'));
        }
    }

    /**
     * update status
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request)
    {
        $tag = Tag::findOrFail($request->id);
        $tag->status = $request->value;
        $tag->save();
    }
}
