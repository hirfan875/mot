<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaHelpers;

/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property string $coupon_code
 * @property bool $status
 * @property int|null $store_id
 * @property bool $is_admin
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $type
 * @property string|null $discount
 * @property int|null $usage_limit
 * @property string|null $total_limit
 * @property string|null $per_user_limit
 * @property int|null $applies_to
 * @property string|null $sub_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read string $display_applies_to
 * @property-read mixed $display_end_date
 * @property-read mixed $display_start_date
 * @property-read string $display_usage_limit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Store|null $store
 * @method static \Database\Factories\CouponFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereAppliesTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCouponCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon wherePerUserLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereTotalLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUsageLimit($value)
 * @mixin \Eloquent
 */
class Coupon extends Model
{
    use HasFactory, MediaHelpers;

    const COUPON_TYPE_FIXED = 'fixed';
    const COUPON_TYPE_PERCENTAGE = 'percentage';

    // usage
    const COUPON_USAGE_UNLIMITED = 1;
    const COUPON_USAGE_LIMITED = 2;

    // applies to
    const COUPON_APPLY_TO_ALL_PRODUCTS = 1;
    const COUPON_APPLY_TO_SPECIFIC_PRODUCTS = 2;
    const COUPON_APPLY_TO_SPECIFIC_CATEGORIES = 3;
    const COUPON_APPLY_TO_SUBTOTAL = 4;
    const COUPON_APPLY_TO_SHIPPING = 5;
    const COUPON_APPLY_TO_BUY_GET = 6;
    const COUPON_APPLY_TO_STORE = 7;

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
        'is_admin' => 'bool',
    ];

    /**
     * coupon categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_coupon');
    }

    /**
     * coupon products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    /**
     * coupon store
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * coupon store
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stores()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * display usage limit attribute
     *
     * @return string
     */
    public function getDisplayUsageLimitAttribute()
    {
        return [
            Coupon::COUPON_USAGE_UNLIMITED => __('Unlimited'),
            Coupon::COUPON_USAGE_LIMITED => __('Limited')
        ][$this->usage_limit];
    }

    /**
     * display applies to attribute
     *
     * @return string
     */
    public function getDisplayAppliesToAttribute()
    {
        return [
            Coupon::COUPON_APPLY_TO_ALL_PRODUCTS => __('All Products'),
            Coupon::COUPON_APPLY_TO_SPECIFIC_PRODUCTS => __('Specific Products'),
            Coupon::COUPON_APPLY_TO_SPECIFIC_CATEGORIES => __('Specific Categories'),
            Coupon::COUPON_APPLY_TO_SUBTOTAL => __('Sub Total'),
            Coupon::COUPON_APPLY_TO_SHIPPING => __('Shipping'),
            Coupon::COUPON_APPLY_TO_BUY_GET => __('Buy & Get Free'),
            Coupon::COUPON_APPLY_TO_STORE => __('Specific Store')
        ][$this->applies_to];
    }

    /**
     * get display_start_date attribute
     */
    public function getDisplayStartDateAttribute()
    {
        return Carbon::parse($this->start_date)->toDateString();
    }

    /**
     * get display_start_date attribute
     */
    public function getDisplayEndDateAttribute()
    {
        return Carbon::parse($this->end_date)->toDateString();
    }

    /**
     * Set coupon code
     *
     * @param string $value
     * @return void
     */
    public function setCouponCodeAttribute($value)
    {
        $this->attributes['coupon_code'] = strtoupper($value);
    }

    /**
     * @return bool
     */
    public function isLimited()
    {
        return $this->usage_limit == self::COUPON_USAGE_LIMITED;
    }
}
