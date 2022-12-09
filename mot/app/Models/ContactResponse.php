<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactResponse extends Model
{
    use HasFactory;

    protected $table = 'contact_response';
    protected $fillable = ['subject', 'message'];
}
