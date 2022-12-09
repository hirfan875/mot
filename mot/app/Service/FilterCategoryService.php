<?php

namespace App\Service;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FilterCategoryService
{
    /**
     * The base query builder instance.
     *
     * @var Builder
     */
    protected $query;

    /** @var bool */
    protected $includeOnlyParentCategory;

    /**
     * Create a new filter tags instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->includeOnlyParentCategory = true;
        $this->query = Category::query();
    }

    /**
     * set active filter
     *
     * @return FilterCategoryService
     */
    public function active(): FilterCategoryService
    {
        $this->query->active();
        return $this;
    }

    /**
     * include sub categories
     *
     * @return FilterCategoryService
     */
    public function withSubcategories($activeSubCategoryOnly=true): FilterCategoryService
    {
        if ($activeSubCategoryOnly) {
            $this->query->with(['category_translates', 'headerSubcategories' => function ($query) {
                $query->where('status', 1);
            }]);
            return $this;
        }
        $this->query->with(['category_translates', 'headerSubcategories']);
        return $this;
    }
    
    public function withAllSubcategories($activeSubCategoryOnly=true): FilterCategoryService
    {
        if ($activeSubCategoryOnly) {
            $this->query->with(['category_translates', 'headerSubcategories' ]);
            return $this;
        }
        $this->query->with(['category_translates', 'headerSubcategories']);
        return $this;
    }

    /**
     * include category Translate
     *
     * @return FilterCategoryService
     */
    public function withCategoryTranslate(): FilterCategoryService
    {
        $this->query->with('category_translate');
        return $this;
    }

    /**
     * include products
     *
     * @return FilterCategoryService
     */
    public function withProducts(): FilterCategoryService
    {
        $this->query->with('products');
        return $this;
    }

    /**
     * set query sort order
     *
     * @param array $orders
     * @return FilterCategoryService
     */
    public function sortBy(array $orders = ['sort_order' => 'asc']): FilterCategoryService
    {
        foreach ($orders as $orderBy => $order) {
            $this->query->orderBy($orderBy, $order);
        }

        return $this;
    }

    /**
     * get latest tags
     *
     * @return FilterCategoryService
     */
    public function latest(): FilterCategoryService
    {
        $this->query->latest();
        return $this;
    }

    /**
     * get filtered tags collection
     *
     * @return Collection
     */
    public function get(): Collection
    {
        if ($this->includeOnlyParentCategory) {
            $this->includeOnlyParentCategories();
        }

        return $this->query->orderBy('sort_order')->get();
    }

    /**
     * get filtered tags collection with pagination
     *
     * @param null $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param null $page
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        if ($this->includeOnlyParentCategory) {
            $this->includeOnlyParentCategories();
        }

        return $this->query->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * get filtered tags count
     *
     * @return int
     */
//    public function count(): int
//    {
//        if ($this->includeOnlyParentCategory) {
//            $this->includeOnlyParentCategories();
//        }
//
//        return $this->query->count();
//    }

    /**
     * get number of tags
     *
     * @param int $total
     * @return FilterCategoryService
     */
    public function take(int $total): FilterCategoryService
    {
        $this->query->take($total);
        return $this;
    }

    /**
     * get single category
     *
     * @return Category
     */
    public function first(): Category
    {
        return $this->query->first();
    }

    /**
     * get single category
     *
     * @return Category
     */
    public function firstOrFail(): Category
    {
        return $this->query->firstOrFail();
    }

    /**
     * set include only parent categories
     *
     * @param bool $includeOnlyParentCategory
     * @return FilterCategoryService
     */
    public function setIncludeOnlyParentCategory(bool $includeOnlyParentCategory = false): FilterCategoryService
    {
        $this->includeOnlyParentCategory = $includeOnlyParentCategory;
        return $this;
    }

    /**
     * include only parent categories
     *
     * @return FilterCategoryService
     */
    protected function includeOnlyParentCategories(): FilterCategoryService
    {
        $this->query->whereNull('parent_id');
        return $this;
    }

    /**
     * include only active
     *
     * @return FilterCategoryService
     */
    public function withOnlyActiveProducts(): FilterCategoryService
    {
        $this->query->with('products');
//                ->whereHas('products', function($query){
//            $query->active();
//        });
        return $this;
    }
}
