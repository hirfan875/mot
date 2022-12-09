<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Service\AttributeService;
use App\Models\AttributeTranslate;

class AttributesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:attributes-list|attributes-create|attributes-edit|attributes-delete', ['only' => ['index','store']]);
        $this->middleware('permission:attributes-create', ['only' => ['create','store']]);
        $this->middleware('permission:attributes-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:attributes-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Attribute::whereNull('parent_id')->orderBy('sort_order', 'asc')->get();

        return view('admin.attributes.index', [
            'title' => __('Attributes'),
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
        return view('admin.attributes.add', [
            'title' => __('Add Attribute'),
            'section_title' => __('Attributes')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {
        $attribute = $attribute->with('attribute_translate')->where('id', $attribute->id)->first();
        return view('admin.attributes.edit', [
            'title' => __('Edit Attribute'),
            'section_title' => __('Attributes'),
            'row' => $attribute
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
            'title' => 'required|max:200'
        ]);

        $attributeService = new AttributeService();
        $attributeService->create($request->toArray());

        return redirect()->route('admin.attributes')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'title' => 'required|max:200'
        ]);

        $attributeService = new AttributeService();
        $attributeService->update($attribute, $request->toArray());

        return redirect()->route('admin.attributes')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function delete(Attribute $attribute)
    {
        $attribute->load(['options']);
        if ( count($attribute->options) > 0 ) {
            return back()->with('error', __('First delete options'));
        }

        $attribute->delete();

        // decrement sort order
        Attribute::whereParentId($attribute->parent_id)->where('sort_order', '>', $attribute->sort_order)->decrement('sort_order');

        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * Show list for changing sort order
     *
     * @return \Illuminate\Http\Response
     */
    public function sorting()
    {
        $attributes = Attribute::whereNull('parent_id')->orderBy('sort_order', 'asc')->get();

        return view('admin.attributes.sorting', [
            'title' => __('Sorting'),
            'section_title' => __('Attributes'),
            'attributes' => $attributes
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
            Attribute::where('id', $r['id'])->update(['sort_order' => $r['order']]);
        }
    }
}
