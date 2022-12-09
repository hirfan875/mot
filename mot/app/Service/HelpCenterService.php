<?php

namespace App\Service;

use App\Models\HelpCenter;
use App\Models\HelpCenterTranslation;

class HelpCenterService
{
    /**
     * create new page
     *
     * @param array $request
     * @return HelpCenter
     */
    public function create(array $request): HelpCenter
    {
        $helpCenter = new HelpCenter();
        $helpCenter->title = $request['title'][getDefaultLocaleId()];
        $helpCenter->description = $request['description'][getDefaultLocaleId()];
        $helpCenter->status = 1;
        $helpCenter->save();
        $this->saveHelpCenterTranslationFromRequest($request, $helpCenter);

        return $helpCenter;
    }

    /**
     * update page
     *
     * @param HelpCenter $helpCenter
     * @param array $request
     * @return HelpCenter
     */
    public function update(HelpCenter $helpCenter, array $request): HelpCenter
    {
        $helpCenter->title = $request['title'][getDefaultLocaleId()];
        $helpCenter->description = $request['description'][getDefaultLocaleId()];
        $helpCenter->status = 1;
        $helpCenter->save();
        $this->updateHelpCenterTranslationFromRequest($request, $helpCenter);

        return $helpCenter;
    }


    /**
     * set HelpCenter translate data from request
     *
     * @param array $request
     * @param HelpCenterTranslation $helpCenterTranslate
     */
    private function saveHelpCenterTranslationFromRequest(array $request, HelpCenter $helpCenter)
    {
        foreach (getLocaleList() as $row) {
            $this->includeHelpCenterTranslationArr($request, $helpCenter, $row);
        }
    }

    private function includeHelpCenterTranslationArr(array $request, HelpCenter $helpCenter, $row)
    {
        $helpCenterTranslate = HelpCenterTranslation::firstOrNew(['help_center_id' => $helpCenter->id, 'language_id' => $row->id]);
        $helpCenterTranslate->help_center_id = $helpCenter->id;
        $helpCenterTranslate->language_id = $row->id;
        $helpCenterTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        $helpCenterTranslate->description = $request['description'][$row->id] ? $request['description'][$row->id] : $request['description'][getDefaultLocaleId()];
        $helpCenterTranslate->status = true;
        $helpCenterTranslate->save();
    }

    /**
     * update page translate data from request
     *
     * @param array $request
     * @param HelpCenterTranslation $helpCenterTranslate
     */
    private function updateHelpCenterTranslationFromRequest(array $request, HelpCenter $helpCenter)
    {
        foreach (getLocaleList() as $row) {
            $this->includeHelpCenterTranslationArr($request, $helpCenter, $row);
        }
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return HelpCenter::where('status', true)->get();
    }
}
