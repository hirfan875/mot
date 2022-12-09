<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeTranslate extends Model
{
    use HasFactory;    
    protected $fillable = ['attribute_id','language_id','language_code','title','type'];
}
