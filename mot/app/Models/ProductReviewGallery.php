<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaHelpers;


/**
 * App\Models\ProductGallery
 *
 * @property int $id
 * @property int|null $product_id
 * @property string|null $image
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGallery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGallery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGallery query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGallery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGallery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGallery whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGallery whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGallery whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGallery whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $return_order_item_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReviewGallery whereReturnOrderItemId($value)
 * @property int $product_review_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReviewGallery whereProductReviewId($value)
 */

class ProductReviewGallery extends Model
{
    use HasFactory, MediaHelpers;

    protected $table = "product_reviews_galleries";
    protected $fillable = ['product_review_id', 'image'];
}
