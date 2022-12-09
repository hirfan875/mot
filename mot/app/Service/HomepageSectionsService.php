<?php

namespace App\Service;

use App\Models\HomepageSorting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class HomepageSectionsService
{
    /**
     * get all home page sections
     *
     * @return Illuminate\Support\Collection
     */
    public function getSections(): Collection
    {
        return HomepageSorting::whereHas('sortable', function (Builder $query) {
            return $query->whereStatus(true);
        })->with('sortable')->orderBy('sort_order')->get();
    }

    /**
     * set section sort order
     *
     * @param Model $model
     * @return void
     */
    public function set_sort_order(Model $model)
    {
        $sort_order = HomepageSorting::count();
        $model->sort()->create(['sort_order' => $sort_order]);
    }

    /**
     * decrement sort order
     *
     * @param int $sort_order
     * @return void
     */
    public function decrement_sort_order(int $sort_order)
    {
        HomepageSorting::where('sort_order', '>', $sort_order)->decrement('sort_order');
    }
}
