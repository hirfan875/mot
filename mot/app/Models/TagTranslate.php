<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagTranslate extends Model
{
    use HasFactory;
    protected $fillable = ['tag_id','language_code','language_id','title'];
}
