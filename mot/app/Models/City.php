<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\City
 *
 * @property int $id
 * @property bool|null $status
 * @property int|null $country_id
 * @property string|null $title
 * @property int|null $type
 * @property string|null $delivery_fee_product
 * @property string|null $delivery_fee_sewing
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country|null $country
 * @method static \Database\Factories\CityFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereDeliveryFeeProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereDeliveryFeeSewing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class City extends Model
{
    use HasFactory;

    // for devliery type
    const FREE = 1;
    const EXPRESS = 2;

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
     * Get the country associated with the city.
     */
    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    /**
     * Get the country associated with the city.
     */
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
