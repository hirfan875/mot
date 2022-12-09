<?php

namespace App\Models;

use App\Traits\MediaHelpers;
use App\Helpers\UtilityHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FlashDeal
 *
 * @property int $id
 * @property bool|null $status
 * @property bool|null $is_approved
 * @property bool|null $expired
 * @property int|null $store_id
 * @property int|null $product_id
 * @property string|null $title
 * @property int|null $discount
 * @property \Illuminate\Support\Carbon|null $starting_at
 * @property \Illuminate\Support\Carbon|null $ending_at
 * @property string|null $image
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read void $discounted_price
 * @property-read mixed $end_date
 * @property-read mixed $end_time
 * @property-read mixed $start_date
 * @property-read mixed $start_time
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Store|null $store
 * @method static \Database\Factories\FlashDealFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereEndingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereStartingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FlashDeal extends Model
{
    use HasFactory, MediaHelpers;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
        'is_approved' => false
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool',
        'is_approved' => 'bool',
        'expired' => 'bool',
        'starting_at' => 'datetime',
        'ending_at' => 'datetime'
    ];

    /**
     * get deal product
     */
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    /**
     * Get the store that owns the deal.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * get start_date attribute
     */
    public function getStartDateAttribute()
    {
        return $this->starting_at->toDateString();
    }

    /**
     * get start_time attribute
     */
    public function getStartTimeAttribute()
    {
        return $this->starting_at->format('g:i A');
    }

    /**
     * get end_date attribute
     */
    public function getEndDateAttribute()
    {
        return $this->ending_at->toDateString();
    }

    /**
     * get end_time attribute
     */
    public function getEndTimeAttribute()
    {
        return $this->ending_at->format('g:i A');
    }

    /**
     * get discounted_price attribute
     *
     * @return void
     */
    public function getDiscountedPriceAttribute()
    {
        return $this->product->price * (1 - $this->discount / 100);
    }

    public function formatedEndingDate()
    {
        return date("m/d/Y H:i:s", strtotime($this->ending_at));
    }

    /**
     * @param $height
     * @param $width
     * @return string
     */
    public function media_image($type=null)
    {
        if ($this->image != null) {
            return UtilityHelpers::getCdnUrl($this->getMedia('image', $type));
        }
        return UtilityHelpers::getCdnUrl(route('resize', [163, 184, 'placeholder.jpg']));
    }
}
