<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country
 *
 * @property int $id
 * @property bool|null $status
 * @property string|null $is_default
 * @property string|null $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @method static \Database\Factories\CountryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Country extends Model
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
        return $this->hasMany(City::class, 'country_id');
    }
    
    /**
     * get all states
     */
    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }
    
    /**
     * get all states
     */
    public function shippingRate()
    {
        return $this->hasMany(ShippingRate::class, 'country_id');
    }
}
