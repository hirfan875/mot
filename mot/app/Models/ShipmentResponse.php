<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentResponse extends Model
{
    use HasFactory;
    protected $fillable = ['order_id','store_order_id','message_time','message_reference','service_invocation_id','tracking_number','label_image_format','graphic_image','shipment_identification_number_awb','file'];
}
