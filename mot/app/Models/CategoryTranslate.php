<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaHelpers;

class CategoryTranslate extends Model
{
    use HasFactory, MediaHelpers;
    
    protected $fillable = ['category_id','language_id','language_code','title','data','meta_title','meta_desc','meta_keyword','image','banner'];
}
