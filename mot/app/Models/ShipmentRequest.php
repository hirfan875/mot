<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentRequest extends Model
{
    use HasFactory;
    protected $fillable = ['store_order_id','insured_value','weight','length','width','height','shiptimestamp','customer_references'];
}
