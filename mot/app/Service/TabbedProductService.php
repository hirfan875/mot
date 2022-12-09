<?php

namespace App\Service;

use App\Models\TabbedSection;

class TabbedProductService
{
    /**
     * create new section
     *
     * @param array $request
     * @return TabbedSection
     */
    public function create(array $request): TabbedSection
    {
        $section = new TabbedSection();

        $section->updateFromRequest($request);

        // set sort order
        $homepageSectionsService = new HomepageSectionsService();
        $homepageSectionsService->set_sort_order($section);

        return $section;
    }

    /**
     * update section
     *
     * @param TabbedSection $section
     * @param array $request
     * @return TabbedSection
     */
    public function update(TabbedSection $section, array $request): TabbedSection
    {
        $section->updateFromRequest($request);

        return $section;
    }
}
