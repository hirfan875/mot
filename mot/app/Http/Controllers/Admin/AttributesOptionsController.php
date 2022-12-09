<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Service\AttributeService;
use App\Models\AttributeTranslate;

class AttributesOptionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:attributes-options-list|attributes-options-create|attributes-options-edit|attributes-options-delete', ['only' => ['index','store']]);
        $this->middleware('permission:attributes-options-create', ['only' => ['create','store']]);
        $this->middleware('permission:attributes-options-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:attributes-options-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function index(Attribute $attribute)
    {
        $records = Attribute::whereParentId($attribute->id)->orderBy('sort_order', 'asc')->get();

        return view('admin.attribute-options.index', [
            'title' => __('Options'),
            'records' => $records,
            'attribute' => $attribute
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function create(Attribute $attribute)
    {
        return view('admin.attribute-options.add', [
            'title' => __('Add Option'),
            'section_title' => __('Options'),
            'attribute' => $attribute
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Attribute $attribute
     * @param Attribute $option
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute, Attribute $option)
    {
        $attribute = $attribute->with('attribute_translate')->where('id', $attribute->id)->first();
        return view('admin.attribute-options.edit', [
            'title' => __('Edit Option'),
            'section_title' => __('Options'),
            'attribute' => $attribute,
            'row' => $option
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Attribute $attribute)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $attributeService = new AttributeService();
        $attributeService->create($request->toArray(), $attribute->id);

        return redirect()->route('admin.attributes.options', ['attribute' => $attribute->id])->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Attribute $attribute
     * @param Attribute $option
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute, Attribute $option)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $attributeService = new AttributeService();
        $attributeService->update($option, $request->toArray());

        return redirect()->route('admin.attributes.options', ['attribute' => $attribute->id])->with('success', __('Record updated successfully.'));
    }

    /**
     * Show list for changing sort order
     *
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function sorting(Attribute $attribute)
    {
        $options = Attribute::whereParentId($attribute->id)->orderBy('sort_order', 'asc')->get();

        return view('admin.attribute-options.sorting', [
            'title' => __('Sorting'),
            'section_title' => __('Options'),
            'attribute' => $attribute,
            'options' => $options
        ]);
    }
}
