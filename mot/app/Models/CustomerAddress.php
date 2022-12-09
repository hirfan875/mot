<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CustomerAddress
 *
 * @property int $id
 * @property int|null $customer_id
 * @property bool $is_default
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $address2
 * @property string|null $address3
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zipcode
 * @property string|null $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Customer|null $customer
 * @method static \Database\Factories\CustomerAddressFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress newQuery()
 * @method static \Illuminate\Database\Query\Builder|CustomerAddress onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereAddress3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|CustomerAddress withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CustomerAddress withoutTrashed()
 * @mixin \Eloquent
 */
class CustomerAddress extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'customer_id', 'name', 'email', 'phone', 'address', 'address2', 'address3', 'aera', 'block', 'street_number',  'house_apartment', 'city', 'state', 'zipcode', 'country'
        ];

    protected  $casts = ['is_default' => 'bool'];
    /**
     * get customer
     *
     * @return Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function __toString()
    {
        $city = isset($this->cities->title) ? $this->cities->title : $this->city;
        $state = isset($this->states->title) ? $this->states->title : $this->state;
        $country = isset($this->countries->title) ? $this->countries->title : $this->country;
        return implode(',', [$this->name, $this->address, $this->block, $this->street_number, $this->house_apartment, $city, $state, $this->zipcode, $country]);
    }

    public function getFormatedAddress(){
        $city = isset($this->cities->title) ? $this->cities->title : $this->city;
        $state = isset($this->states->title) ? $this->states->title : $this->state;
        $country = isset($this->countries->title) ? $this->countries->title : $this->country;
        return implode(', ', [$this->address, $this->block, $this->street_number, $this->house_apartment, $city, $state, $country]);
    }

    /**
     * Get the city
     */
    public function cities()
    {
        return $this->hasOne(City::class, 'id', 'city');
    }

    /**
     * Get the state
    */
    public function states()
    {
        return $this->hasOne(State::class, 'id', 'state');
    }

    /**
     * Get the country
     */
    public function countries()
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }
}
