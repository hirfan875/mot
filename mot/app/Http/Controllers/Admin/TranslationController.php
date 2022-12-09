<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Service\LanguageService;
use App\Service\TranslationService;

class TranslationController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth']);
        $this->middleware('permission:translation-list|translation-create|translation-edit|translation-delete', ['only' => ['index','store']]);
        $this->middleware('permission:translation-create', ['only' => ['create','store']]);
        $this->middleware('permission:translation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:translation-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Language $language)
    {
        $baseQuery = Translation::whereLanguageId($language->id);

        if ($request->keyword) {
            $keyWord = $request->keyword;
            $baseQuery = $baseQuery->where(function($query) use($keyWord){
                $query->where('key', 'like', '%' . $keyWord . '%')->Orwhere('translate', 'like', '%' . $keyWord . '%');
            });
        }
        $records = $baseQuery->paginate(25);

        return view('admin.translation.index', [
            'title' => __('Translation'),
            'records' => $records,
            'language' => $language
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Language $language) {
        return view('admin.translation.add', [
            'title' => __('Add Translation'),
            'section_title' => __('Translation'),
            'language' => $language
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Language $language, Request $request) {

        $request->validate([
            'key' => 'required',
            'translate' => 'required',
        ]);

        $translationService = new TranslationService();
        $translationService->create($request->toArray(), $language->id);

        return redirect()->route('admin.translation', ['language' => $language->id])->with('success', __('Record added successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Language $language
     * @param  Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language, Translation $translate) {
        return view('admin.translation.edit', [
            'title' => __('Edit Translation'),
            'section_title' => __('Translation'),
            'row' => $translate,
            'language' => $language
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language, Translation $translate) {
        $request->validate([
            'key' => 'required',
            'translate' => 'required',
        ]);
        $translationService = new TranslationService();
        $translationService->update($translate, $request->toArray());

        return redirect()->route('admin.translation', ['language' => $language->id])->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language, Translation $translate) {

        $translate->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * update status
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Language $language, Request $request) {

        $translation = Translation::findOrFail($request->id);
        $translation->status = $request->value;
        $translation->save();
    }

}
