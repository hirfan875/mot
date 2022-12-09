<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProfileTranslate extends Model
{
    use HasFactory;
     protected $fillable = ['store_id','language_id','language_code','name','description','return_and_refunds','policies'];
}
