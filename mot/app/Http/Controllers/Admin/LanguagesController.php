<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Service\LanguageService;
use App\Service\Media;

class LanguagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:languages-list|languages-create|languages-edit|languages-delete', ['only' => ['index','store']]);
        $this->middleware('permission:languages-create', ['only' => ['create','store']]);
        $this->middleware('permission:languages-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:languages-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Language::orderBy('status' , 'desc')->get();

        return view('admin.languages.index', [
            'title' => __('Languages'),
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
        return view('admin.languages.add', [
            'title' => __('Add Language'),
            'section_title' => __('Languages')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Language $language
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language)
    {
        return view('admin.languages.edit', [
            'title' => __('Edit Language'),
            'section_title' => __('Languages'),
            'row' => $language
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
            'code' => 'required|max:2|unique:languages',
        ]);

        $languageService = new LanguageService();
        $languageService->create($request->toArray());

        return redirect()->route('admin.languages')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Language $language
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language)
    {
        $request->validate([
            'title' => 'required|max:100',
            'code' => 'required|max:2|unique:languages,code,'.$language->id,
        ]);

        $languageService = new LanguageService();
        $languageService->update($language, $request->toArray());

        return redirect()->route('admin.languages')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Language $language
     * @return \Illuminate\Http\Response
     */
    public function delete(Language $language)
    {
        Media::delete($language->icon);
        $language->delete();

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
        $language = Language::findOrFail($request->id);
        $language->status = $request->value;
        $language->save();
    }

    /**
     * set selected lan$language as default
     *
     * @param Language $language
     * @return \Illuminate\Http\Response
     */
    public function setDefault(Language $language)
    {
        /** update all languages default status except selected */
        Language::where('id', '<>', $language->id)->update(['is_default' => null]);

        /** update selected language default flag */
        $language->is_default = 'Yes';
        $language->save();

        return back()->with('success', __('Record updated successfully.'));
    }
}
