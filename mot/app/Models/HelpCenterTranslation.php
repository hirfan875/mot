<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpCenterTranslation extends Model
{
    use HasFactory;
    protected $table = 'help_center_translations';
    protected $fillable = ['help_center_id', 'title', 'description', 'language_id', 'language_code','title','type'];
}
