<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreReview
 *
 * @property int $id
 * @property string $review
 * @property int $rating
 * @property int $store_id
 * @property int $customer_id
 * @property int $store_order_id
 * @property int $is_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\Product $store
 * @method static \Database\Factories\StoreReviewFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $comment
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreReview whereStoreOrderId($value)
 * @property-read mixed $review_title
 */
class StoreReview extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'customer_id', 'language_id', 'store_order_id', 'is_approved', 'rating', 'comment'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function getReviewTitleAttribute()
    {
        return '';
    }
}
