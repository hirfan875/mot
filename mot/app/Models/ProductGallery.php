<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaHelpers;
use DB;

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
 */
class ProductGallery extends Model
{
    use HasFactory, MediaHelpers;

     public function deleteImage($productId)
    {
        // update remaining product media gallery sorting order
        ProductGallery::whereProductId($productId);
        $productGallery = ProductGallery::whereProductId($productId)->first();
        if($productGallery->sort_order != null) {
           $productGallery =  $productGallery->where('sort_order', '>', $productGallery->sort_order)
            ->update([
                'sort_order' => DB::raw('sort_order-1')
            ]);
        }
        
        $this->delete();
    }
}
