<?php

namespace App\Models;

use App\Service\FilterProductsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TrendingProduct
 *
 * @property int $id
 * @property bool|null $status
 * @property string|null $title
 * @property string|null $type
 * @property int|null $category_id
 * @property string|null $products_type
 * @property int|null $tag_id
 * @property string|null $view_all_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $edit_url
 * @property-read \App\Models\HomepageSorting|null $sort
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereProductsType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrendingProduct whereViewAllUrl($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Category|null $category
 * @method static \Database\Factories\TrendingProductFactory factory(...$parameters)
 */
class TrendingProduct extends Model
{
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @param null $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get_products($perPage = null, $columns = ['*'], $pageName = 'page', $page = null){
        /**
         * This should preferably return a relationship .. currently it is too complicated to do that.
         * Keeping relationship here will make it more SOLID
         */
        $filterProductService = new FilterProductsService();
        return $filterProductService->byTrendingProductSection($this)->paginate($perPage, $columns, $pageName , $page);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool',
    ];

    /**
     * Get the sort order
     */
    public function sort()
    {
        return $this->morphOne(HomepageSorting::class, 'sortable');
    }

    /**
     * get edit_url attribute
     */
    public function getEditUrlAttribute()
    {
        return route('admin.trending.products.edit', ['item' => $this->id]);
    }
}
