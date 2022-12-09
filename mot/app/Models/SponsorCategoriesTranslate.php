<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaHelpers;
use App\Helpers\UtilityHelpers;

class SponsorCategoriesTranslate extends Model
{
     use HasFactory, MediaHelpers;
     protected $fillable = ['sponsor_category_id','language_id','title','image','button_text','button_url'];
     
     public function media_image($type=null)
    {
        if ($this->image != null) {
            return UtilityHelpers::getCdnUrl($this->getMedia('image', $type));
        }
        return UtilityHelpers::getCdnUrl(route('resize', [163, 184, 'placeholder.jpg']));
    }
}
