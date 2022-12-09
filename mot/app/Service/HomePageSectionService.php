<?php

namespace App\Service;

use App\Models\HomepageSorting;
use Illuminate\Database\Eloquent\Builder;

/**
 * Most Other similar services that relate to sections, like TabbedProducts, Sponsored Categories, Sliders etc
 * should be combined and placed here.
 * Class HomePageSectionService
 * @package App\Service
 */
class HomePageSectionService
{
    /**
     * @return HomepageSorting[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     *
     */
    public function getSections()
    {
        return HomepageSorting::query()
            ->whereHas('sortable', function (Builder $query) {
                return $query->whereStatus(true);
            })
            ->with('sortable')->orderBy('sort_order')->get();
    }
}
