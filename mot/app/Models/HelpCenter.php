<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpCenter extends Model
{
    use HasFactory;

    /**
     * get page translate
     *
     * @return \Illuminate\Support\Collection
     */
    public function help_center_translate()
    {
        return $this->hasMany(HelpCenterTranslation::class, 'help_center_id');
    }

    /**
     * get page translates
     *
     * @return \Illuminate\Support\Collection
     */
    public function help_center_translates()
    {
        return $this->hasOne(HelpCenterTranslation::class, 'help_center_id')->where('language_id', getLocaleId(app()->getLocale()));
    }
}
