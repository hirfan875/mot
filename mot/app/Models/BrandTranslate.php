<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandTranslate extends Model
{
    use HasFactory;
    
    protected $fillable = ['brand_id','language_id','title','data','meta_title','meta_desc','meta_keyword'];
}
