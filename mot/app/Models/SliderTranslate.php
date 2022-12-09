<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaHelpers;

class SliderTranslate extends Model
{
    use HasFactory, MediaHelpers;
    protected $fillable = ['banner_id','language_id','image','button_text','button_url'];
}
