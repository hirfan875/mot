<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTranslate extends Model
{
    use HasFactory;
    protected $fillable = ['page_id','language_id','title','data','meta_title','meta_desc','meta_keyword'];
}
