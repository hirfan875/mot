<?php

namespace App\Models;

use App\Service\FilterProductsService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TabbedSection
 *
 * @property int $id
 * @property bool|null $status
 * @property string|null $title
 * @property string|null $type
 * @property int|null $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read mixed $edit_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $home_page_products
 * @property-read int|null $home_page_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\HomepageSorting|null $sort
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection query()
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TabbedSection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TabbedSection extends Model
{
    use HasFactory;

    const HOME_PAGE_GRID_SIZE = 4;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool',
    ];

    /**
     * section products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'tabbed_products');
    }

    /**
     * section products
     *
     * @param null $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get_products($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        /**
         * See comments in TrendingProducts
         */
        $filterProductService = new FilterProductsService();
        return $filterProductService->byTabbedProductSection($this)->paginate($perPage, $columns, $pageName , $page);
    }

    /**
     * section category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

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
        return route('admin.tabbed.products.edit', ['item' => $this->id]);
    }

    /**
     * It better suits the model
     * @param array $request
     * @return TabbedSection
     */
    public function updateFromRequest(array $request)
    {
        $this->title = $request['title'];
        $this->type = $request['type'];

        if ($request['type'] === 'category') {
            $this->category_id = $request['category_id'];
            $this->save();
            return $this;
        }

        // attach products
        if ($request['type'] === 'product') {
            $this->products()->sync($request['products']);
        }

        $this->save();
        return $this;
    }
}
