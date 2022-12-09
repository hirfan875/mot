<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\MediaHelpers;

/**
 * App\Models\ProductReview
 *
 * @property int $id
 * @property string $comment
 * @property int $rating
 * @property int $customer_id
 * @property int $order_item_id
 * @property bool $is_approved
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\OrderItem $order_item
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductReview onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductReview withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductReview withoutTrashed()
 * @mixin \Eloquent
 * @method static \Database\Factories\ProductReviewFactory factory(...$parameters)
 */
class ProductReview extends Model
{
    use HasFactory, SoftDeletes, MediaHelpers;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_approved' => 'bool'
    ];

    /**
     * Get the customer that owns the review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the order item that owns the review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItem::class);
    }
    
    /**
     * get product gallery
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gallery() {
        return $this->hasMany(ProductReviewGallery::class, 'product_review_id');
    }
}
