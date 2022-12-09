<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevices extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'type', 'customer_id', 'is_general_notifications', 'is_order_notifications'];
}
