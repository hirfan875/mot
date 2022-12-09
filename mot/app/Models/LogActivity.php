<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id','store_id','category_id','url','ip','country','devices','total','type'];
}
