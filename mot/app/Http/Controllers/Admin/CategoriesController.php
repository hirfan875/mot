<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Rules\UniqueCategory;
use App\Service\CategoryService;
use App\Service\FilterCategoryService;
use App\Service\Media;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:categories-list|categories-create|categories-edit|categories-delete', ['only' => ['index','store']]);
        $this->middleware('permission:categories-create', ['only' => ['create','store']]);
        $this->middleware('permission:categories-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:categories-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterCategoryService $filterCategoryService)
    {
        $records = $filterCategoryService->withAllSubcategories(false)->get();

        return view('admin.categories.index', [
            'title' => __('Categories'),
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FilterCategoryService $filterCategoryService)
    {
        $categories = $filterCategoryService->withSubcategories()->get();

        return view('admin.categories.add', [
            'title' => __('Add Category'),
            'section_title' => __('Categories'),
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, FilterCategoryService $filterCategoryService)
    {
        $categories = $filterCategoryService->withSubcategories()->withCategoryTranslate()->get();
        $category = $category->with('category_translate')->where('id', $category->id)->first();
        return view('admin.categories.edit', [
            'title' => __('Edit Category'),
            'section_title' => __('Categories'),
            'row' => $category,
            'categories' => $categories
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
            'title' => ['required', new UniqueCategory($request->parent_id)],
            'commission' => [Rule::requiredIf(!isset($request->parent_id))],
//            'image' => 'sometimes|mimes:jpg,jpeg,png',
//            'banner' => 'sometimes|mimes:jpg,jpeg,png'
        ]);

        $categoryService = new CategoryService();
        $categoryService->create($request->toArray());

        return redirect()->route('admin.categories')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => ['required', new UniqueCategory($request->parent_id, $category->id)],
            'commission' => [Rule::requiredIf(!isset($request->parent_id))],
//            'image' => 'sometimes|mimes:jpg,jpeg,png',
//            'banner' => 'sometimes|mimes:jpg,jpeg,png'
        ]);

        $categoryService = new CategoryService();
        $categoryService->update($category, $request->toArray());

        return redirect()->route('admin.categories')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function delete(Category $category)
    {
        $category->load(['subcategories']);
        if ( count($category->subcategories) > 0 ) {
            return back()->with('error', __('First delete sub categories.'));
        }

        Media::delete($category->image);
        $category->delete();

        // decrement sort order
        Category::where('sort_order', '>', $category->sort_order)->decrement('sort_order');

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
        $category = Category::findOrFail($request->id);
        $category->status = $request->value;
        $category->save();
    }

    /**
     * Show list for changing sort order
     *
     * @return \Illuminate\Http\Response
     */
    public function sorting(FilterCategoryService $filterCategoryService)
    {
        $categories = $filterCategoryService->withSubcategories()->get();

        return view('admin.categories.sorting', [
            'title' => __('Sorting'),
            'section_title' => __('Categories'),
            'categories' => $categories
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
            Category::where('id', $r['id'])->update(['sort_order' => $r['order']]);
        }
    }
}
