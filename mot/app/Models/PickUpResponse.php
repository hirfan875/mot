<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickUpResponse extends Model
{
    use HasFactory;
    protected $fillable = ['store_order_id','dispatch_confirmation'];
}
