<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackShipmentResponse extends Model
{
    use HasFactory;
    protected $fillable = ['store_order_id','awb_number','date','time','event_code','track_event_desc','area_code','area_desc'];
}
