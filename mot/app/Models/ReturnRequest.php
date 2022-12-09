<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReturnRequest
 *
 * @property int $id
 * @property int $status
 * @property int $store_order_id
 * @property bool $is_archive
 * @property int $received_expected
 * @property string $notes
 * @property int|null $tracking_id
 * @property string|null $company_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReturnRequestGallery[] $gallery
 * @property-read int|null $gallery_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReturnOrderItems[] $return_order_items
 * @property-read int|null $return_order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ReturnRequest[] $saveGallery
 * @property-read int|null $save_gallery_count
 * @property-read \App\Models\StoreOrder $store_order
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest active()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereIsArchive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereReceivedExpected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereStoreOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereTrackingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\ReturnRequestFactory factory(...$parameters)
 */
class ReturnRequest extends Model
{
    use HasFactory;

    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;

    const RECEIVED_PENDING = 0;
    const RECEIVED_EXPECTED = 1;
    const RECEIVED_NOTEXPECTED = 2;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_archive' => 'bool'
    ];

    protected $fillable = ['status', 'store_order_id', 'notes', 'tracking_id', 'company_name'];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function store_order()
    {
        return $this->belongsTo(StoreOrder::class);
    }

    public function return_order_items()
    {
        return $this->hasMany(ReturnOrderItems::class);
    }

    public function saveGallery()
    {
        return $this->belongsToMany(ReturnRequest::class, ReturnRequestGallery::class, 'return_request_id', 'image')->withTimestamps();
    }

    public function gallery()
    {
        return $this->hasMany(ReturnRequestGallery::class, 'return_request_id');
    }

    /**
     * @return bool
     */
    public function isApproved() : bool
    {
        return $this->status === self::APPROVED;
    }

    function getStatus(){
        $statusName = [
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        ];
        return $statusName[$this->status] ?? 'Pending' ;
    }

    /**
     * Scope a query to only include active products
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', '<', self::REJECTED)->whereIsArchive(false);
    }
}
