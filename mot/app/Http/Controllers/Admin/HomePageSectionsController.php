<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSorting;
use Illuminate\Http\Request;

class HomePageSectionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show list for changing sort order
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = HomepageSorting::with('sortable')->orderBy('sort_order')->get();

        return view('admin.homepage-sections.sorting', [
            'title' => __('Sections Sorting'),
            'records' => $records
        ]);
    }

    /**
     * Update sort order
     *
     * @param Request $request
     * @return void
     */
    public function sort(Request $request)
    {
        foreach ( $request['items'] as $r ) {
            HomepageSorting::where('id', $r['id'])->update(['sort_order' => $r['order']]);
        }
    }
}
