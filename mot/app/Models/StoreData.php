<?php

namespace App\Models;

use App\Traits\MediaHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreData
 *
 * @property int $id
 * @property int $store_id
 * @property int|null $status
 * @property string|null $banner
 * @property string|null $logo
 * @property string|null $description
 * @property string|null $return_and_refunds
 * @property string|null $policies
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData adminList()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData approved()
 * @method static \Database\Factories\StoreDataFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData pending()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData rejected()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData wherePolicies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData whereReturnAndRefunds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreData whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreData extends Model
{
    use HasFactory, MediaHelpers;
    
    protected $fillable = [ 'id', 'title', 'store_id', 'status', 'banner', 'logo', 'description', 'return_and_refunds ', 'policies', 'meta_title', 'meta_desc', 'meta_keyword', 'sort_order' ];


    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING
    ];

    /**
     * get store
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    

    /**
     * Scope a query to exclude rejected stores
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdminList($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    /**
     * Scope a query to only include pending list
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->whereStatus(self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include pending list
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->whereStatus(self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include pending list
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->whereStatus(self::STATUS_REJECTED);
    }

    /**
     * check whether record is rejected?
     *
     * @return bool
     */
    public function is_rejected()
    {
        if ($this->status === self::STATUS_REJECTED) {
            return true;
        }

        return false;
    }

    /**
     * check whether record is in pending?
     *
     * @return bool
     */
    public function is_pending()
    {
        if ($this->status === self::STATUS_PENDING) {
            return true;
        }

        return false;
    }
}
