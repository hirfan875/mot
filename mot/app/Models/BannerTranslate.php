<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaHelpers;

class BannerTranslate extends Model
{
    use HasFactory, MediaHelpers;
    protected $fillable = ['banner_id','language_id','title','data','button_text','button_url','image','image_mobile'];
}
