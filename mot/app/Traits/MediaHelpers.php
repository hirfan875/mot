<?php

namespace App\Traits;
use App\Helpers\UtilityHelpers;

use Storage;

trait MediaHelpers
{
    /**
     * get media size
     *
     * @param string $name
     * @param string $size
     * @return string
     */
    public function getMedia(string $name, string $size = 'original'): string
    {

        $attribute = $this->attributes[$name];
        if (!$attribute) {
            return $this->getPlaceholder($name);
        }
        $path = "{$size}/{$attribute}";
        $check_file = Storage::exists($path);
        if ($check_file) {
            return UtilityHelpers::getCdnUrl("/storage/{$path}") ;
        }

        return $this->getPlaceholder($name);
    }

    /**
     * get original/placeholder image
     *
     * @param string $name
     * @return string
     */
    private function getPlaceholder(string $name): string
    {
        $attribute = $this->attributes[$name];
        if (!$attribute) {
            $attribute = config('media.placeholder');
        }

        $path = "storage/original/{$attribute}";
        return $path;
    }

    /**
     * get original media
     *
     * @param string $name
     * @return string/null
     */
    public function getOriginalMedia(string $name): ?string
    {
        return $this->attributes[$name];
    }
}
