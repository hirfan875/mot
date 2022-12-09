<?php

namespace App\Service;

use App\Models\Language;

class LanguageService
{
    /**
     * create new language
     *
     * @param array $request
     * @return Language
     */
    public function create(array $request): Language
    {
        $language = new Language();

        // set this language as default
        if ( isset($request['is_default']) ) {
            $language->is_default = $request['is_default'];
        }

        $language->title = $request['title'];
        $language->native = $request['native'];
        $language->code = $request['code'];
        $language->direction = $request['direction'];
        $language->emoji = $request['emoji'];
        $language->emoji_uc = $request['emoji_uc'];
        // $language->icon = Media::handle($request, 'icon');

        $language->save();

        return $language;
    }

    /**
     * update language
     *
     * @param Language $language
     * @param array $request
     * @return Language
     */
    public function update(Language $language, array $request): Language
    {
        $language->title = $request['title'];
        $language->native = $request['native'];
        $language->code = $request['code'];
        $language->direction = $request['direction'];
        $language->emoji = $request['emoji'];
        $language->emoji_uc = $request['emoji_uc'];
        // $language->icon = Media::handle($request, 'icon', $language);

        $language->save();

        return $language;
    }

    /**
     * @return Language[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getActive()
    {
        return Language::where('status', true)->get();
    }
}
