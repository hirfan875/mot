<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool',
    ];
    
    /**
     * get all cities
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }
    
    /**
     * Get the country associated with the city.
     */
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
