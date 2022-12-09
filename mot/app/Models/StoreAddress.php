<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreAddress
 *
 * @property int $id
 * @property int|null $store_id
 * @property string|null $name
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
 * @property-read \App\Models\Store|null $store
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereAddress3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreAddress whereZipcode($value)
 * @mixin \Eloquent
 */
class StoreAddress extends Model
{
    use HasFactory;

    /**
     * get address store
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function emptyAddressColumns()
    {
        return (object) [
            'name' => '',
            'phone' => '',
            'address' => '',
            'address2' => '',
            'address3' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
            'country' => ''
        ];
    }
}
