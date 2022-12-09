<?php

namespace App\Service;

use App\Models\Category;
use App\Models\CategoryTranslate;

class CategoryService
{
    /**
     * create new category
     *
     * @param array $request
     * @return Category
     */
    public function create(array $request): Category
    {
        $category = new Category();

        $parent_id = null;
        if (!empty($request['parent_id'])) {
            $parent_id = $request['parent_id'];
        }

        $this->saveCategoryFromRequest($category, $request, $parent_id);
        // upload new file
        if (isset($request['image'][getDefaultLocaleId()])) {
            $category->image = Media::upload($request['image'][getDefaultLocaleId()], true, true, 'image');
        }
        // upload new file
        if (isset($request['banner'][getDefaultLocaleId()])) {
            $category->banner = Media::upload($request['banner'][getDefaultLocaleId()], true, true, 'image');
        }

//        $category->image = Media::handle($request, 'image');
//        $category->banner = Media::handle($request, 'banner');

        $category->sort_order = $this->getNextSortOrderNumber($parent_id);
        $category->save();
        $results = $this->saveCategoryTranslateFromRequest($request, $category);

        // increment sort order
        Category::where('sort_order', '>=', $category->sort_order)->where('id', '!=', $category->id)->increment('sort_order');

        return $category;
    }

    /**
     * update category
     *
     * @param Category $category
     * @param array $request
     * @return Category
     */
    public function update(Category $category, array $request): Category
    {
        $old_parent_id = $category->parent_id;
        $parent_id = null;
        if (!empty($request['parent_id'])) {
            $parent_id = $request['parent_id'];
        }

        $this->saveCategoryFromRequest($category, $request, $parent_id);
        // upload new file
        if (isset($request['image'][getDefaultLocaleId()])) {
            $category->image = Media::upload($request['image'][getDefaultLocaleId()], true, true, 'image');
        }
        // upload new file
        if (isset($request['banner'][getDefaultLocaleId()])) {
            $category->banner = Media::upload($request['banner'][getDefaultLocaleId()], true, true, 'image');
        }
//        $category->image = Media::handle($request, 'image', $category);
//        $category->banner = Media::handle($request, 'banner', $category);

        if (isset($request['slug'])) {
            $category->slug = str_replace(" ", "-", $request['slug']);
        }
        $category->save();
        $results = $this->updateCategoryTranslateFromRequest($request, $category);

        $this->updateCategorySortOrder($category, $old_parent_id, $parent_id);

        return $category;
    }

    /**
     * save category from request
     *
     * @param Category $category
     * @param array $request
     * @param int|null $parent_id
     * @return void
     */
    private function saveCategoryFromRequest(Category $category, array $request, ?int $parent_id)
    {
        $category->parent_id = $parent_id;
        $category->title = $request['title'][getDefaultLocaleId()];
        $category->commission = $request['commission'];
        $category->data = $request['data'][getDefaultLocaleId()];
        $category->meta_title = $request['meta_title'][getDefaultLocaleId()];
        $category->meta_desc = $request['meta_desc'][getDefaultLocaleId()];
        $category->meta_keyword = $request['meta_keyword'][getDefaultLocaleId()];

        if (isset($request['google_category'])) {
            $category->google_category = $request['google_category'];
        }
    }

    /**
     * set category translate data from request
     *
     * @param array $request
     * @param CategoryTranslate $pategoryTranslate
     */
    private function saveCategoryTranslateFromRequest(array $request, Category $category)
    {
        foreach (getLocaleList() as $row) {
            $this->includeCategoryTranslateArr($request, $category, $row);
        }
    }

    private function includeCategoryTranslateArr(array $request, Category $category, $row)
    {
        $categoryTranslate = CategoryTranslate::firstOrNew(['category_id' => $category->id, 'language_id' => $row->id]);
        $categoryTranslate->category_id = $category->id;
        $categoryTranslate->language_id = $row->id;
        $categoryTranslate->language_code = $row->code;
        $categoryTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        $categoryTranslate->data = $request['data'][$row->id] ? $request['data'][$row->id] : $request['data'][getDefaultLocaleId()];
        if (isset($request['image'][$row->id])) {
            $categoryTranslate->image = Media::upload($request['image'][$row->id], true, true, 'image');
        }
        if (isset($request['banner'][$row->id])) {
            $categoryTranslate->banner = Media::upload($request['banner'][$row->id], true, true, 'image');
        }
        $categoryTranslate->meta_title = $request['meta_title'][$row->id] ? $request['meta_title'][$row->id] : $request['meta_title'][getDefaultLocaleId()];
        $categoryTranslate->meta_desc = $request['meta_desc'][$row->id] ? $request['meta_desc'][$row->id] : $request['meta_desc'][getDefaultLocaleId()];
        $categoryTranslate->meta_keyword = $request['meta_keyword'][$row->id] ? $request['meta_keyword'][$row->id] : $request['meta_keyword'][getDefaultLocaleId()];
        $categoryTranslate->status = true;
        $categoryTranslate->save();
    }

    /**
     * update category translate data from request
     *
     * @param array $request
     * @param CategoryTranslate $categoryTranslate
     */
    private function updateCategoryTranslateFromRequest(array $request, Category $category)
    {
        foreach (getLocaleList() as $row) {
            $this->includeCategoryTranslateArr($request, $category, $row);
        }
    }

    /**
     * get next sort order number
     *
     * @param int|null $parent_id
     * @return int
     */
    private function getNextSortOrderNumber(?int $parent_id = null): int
    {
        $sort_order = Category::whereParentId($parent_id)->max('sort_order');
        if ($sort_order === null) {
            $sort_order = 0;
        }

        if ($sort_order === 0 && $parent_id != null) {
            $parent_category = Category::find($parent_id);
            return $parent_category->sort_order + 1;
        }

        return $sort_order + 1;
    }

    /**
     * update category sort order if parent change
     *
     * @param Category $category
     * @param int|null $old_parent_id
     * @param int|null $parent_id
     * @return void
     */
    private function updateCategorySortOrder(Category $category, ?int $old_parent_id, ?int $parent_id)
    {
        if ($old_parent_id == $parent_id) {
            return;
        }

        Category::where('sort_order', '>', $category->sort_order)->decrement('sort_order');

        // set new sort order
        $category->sort_order = $this->getNextSortOrderNumber($parent_id);
        $category->save();

        Category::where('sort_order', '>=', $category->sort_order)->where('id', '!=', $category->id)->increment('sort_order');
    }

    public function getTopLevelCategories()
    {
        $categories = Category::select('id', 'title', 'slug')->whereNull('parent_id')->active()->get();
        return $categories;
    }

    public function getCategory($category_slug)
    {
        $category = Category::select('id', 'title')->where('slug', $category_slug)->first();
        return $category;
    }

    /**
     * @param string $slug
     * @return Category|null
     */
    public function getBySlug(string $slug): ?Category
    {
        $category = Category::whereSlug($slug)->first();
        return $category;
    }

    /**
     * @param string $title
     * @return Category|null
     */
    public function getByTitle(string $title): ?Category
    {
        $category = Category::whereTitle($title)->first();
        return $category;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $categories = Category::get();
        return $categories;
    }

    public function getSubCategories($category_id)
    {
        $categories = Category::with('parent')->select('id', 'title', 'slug')->where('parent_id', $category_id)->active()->get();
        return $categories;
    }
    
    public function getSubCategoriesByIds($category_ids)
    {
        $categories = Category::with('parent')->select('id', 'title', 'slug')->whereIn('parent_id', $category_ids)->active()->get();
        return $categories;
    }

    /**
     * @param int $id
     * @return Category|null
     */
    public function getById($id): ?Category
    {
        $category = Category::find($id);
        return $category;
    }

    public function getByProductIds($productsIds)
    {
        $categories = Category::whereHas('products', function($categoriesQuery) use($productsIds){
            $categoriesQuery->whereIn('product_id', $productsIds);
        })->active()->get();

        return $categories;
    }

    public function getTopLevelByProductIds($productsIds)
    {
        $categories = Category::whereHas('products', function($categoriesQuery) use($productsIds){
            $categoriesQuery->whereIn('product_id', $productsIds);
        })->whereNull('parent_id')->active()->get();

        return $categories;
    }

    public function findTopParent($id)
    {
        $cat = Category::find($id);
        $parent = optional($cat)->parent;

        return $parent ? $this->findTopParent($parent->id) : $cat;
    }

}
