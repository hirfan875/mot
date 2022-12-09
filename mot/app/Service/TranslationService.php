<?php

namespace App\Service;

use App\Models\Translation;

class TranslationService {

    /**
     * create new Translation
     *
     * @param array $request
     * @return Translation
     */
    public function create(array $request, $language_id): Translation {

        $translation = new Translation();
        $translation->language_id = $language_id;
        $translation->key = $request['key'];
        $translation->translate = $request['translate'];
        $translation->state = $request['state'];
        $translation->save();
        return $translation;
    }

    /**
     * update Translation
     *
     * @param Translation $translation
     * @param array $request
     * @return Translation
     */
    public function update(Translation $translation, array $request): Translation {

        $translation->key = $request['key'];
        $translation->translate = $request['translate'];
        $translation->state = $request['state'];
        $translation->save();

        return $translation;
    }

}
