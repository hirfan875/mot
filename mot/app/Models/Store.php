<?php

namespace App\Models;

use App\Helpers\UtilityHelpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\StoreProfileTranslate;

/**
 * App\Models\Store
 *
 * @property int $id
 * @property bool|null $status
 * @property string|null $slug
 * @property string|null $name
 * @property int|null $type
 * @property string|null $tax_id
 * @property string|null $tax_id_type
 * @property string|null $tax_office
 * @property string|null $identity_no
 * @property int|null $commission
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property int|null $country_id
 * @property string|null $zipcode
 * @property string|null $phone
 * @property int $is_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country|null $country
 * @property-read mixed $description
 * @property-read mixed $image_url
 * @property-read mixed $policies
 * @property-read mixed $rating
 * @property-read mixed $return_and_refunds
 * @property-read string $display_type
 * @property-read \App\Models\StoreStaff|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\StoreAddress|null $return_address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StoreReview[] $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StoreStaff[] $staff
 * @property-read int|null $staff_count
 * @property-read \App\Models\StoreData|null $store_data
 * @method static \Illuminate\Database\Eloquent\Builder|Store approved()
 * @method static \Database\Factories\StoreFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereIdentityNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereTaxIdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereTaxOffice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereZipcode($value)
 * @mixin \Eloquent
 * @property string|null $submerchant_key
 * @property mixed legal_name
 * @property mixed iban
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereSubmerchantKey($value)
 */
class Store extends Model
{
    use HasFactory, HasSlug;

    const INDIVIDUAL = 0; //AKA PERSONAL
    const PRIVATE_COMPANY = 1;
    const LIMITED_STOCK_COMPANY = 2;

    const SMALLEST_POSITIVE_RATING = 4;

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_DISABLED = 3;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
        'is_approved' => self::STATUS_PENDING
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool'
    ];

    /**
     * Get the options for generating the slug.
     *
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Scope a query to only include pending list
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->whereIsApproved(self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include pending list
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->whereIsApproved(self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include reject list
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->whereIsApproved(self::STATUS_REJECTED);
    }

    /**
     * display store type
     *
     * @return string
     */
    public function getdisplayTypeAttribute()
    {
        return [
            Store::INDIVIDUAL => __('Individual'),
            Store::PRIVATE_COMPANY => __('Private Company'),
            Store::LIMITED_STOCK_COMPANY => __('Limited Stock Company')
        ][$this->type];
    }

    /**
     * Get the country that owns the store.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * get store staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staff()
    {
        return $this->hasMany(StoreStaff::class);
    }

    /**
     * get store products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * get store reviews
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(StoreReview::class);
    }

    /**
     * get single address associated with the store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function return_address()
    {
        return $this->hasOne(StoreAddress::class);
    }

    public function getPositiveRatingPercent()
    {
        $date = Carbon::now()->subYear()->timestamp;
        $last_year_rating_count = $this->reviews()
            ->where('is_approved', true)
            ->where('created_at', '>=', $date)->count();
        if ($last_year_rating_count <= 0) {
            return 0;
        }
        $last_year_positive_count = $this->reviews()
            ->where('is_approved', true)
            ->where('rating', '>=', self::SMALLEST_POSITIVE_RATING)
            ->where('created_at', '>=', $date)
            ->count();

        return  round((100 * $last_year_positive_count) / $last_year_rating_count);
    }

    public function lifetimeRatingCount()
    {
        return  $this->reviews()->where('is_approved', true)->count();
//        return  $this->reviews()->where('is_approved', true)->count();
    }

    /**
     * TODO  Think a better way to cache this value, perhaps within a DB field
     */
    public function getRatingAttribute()
    {
        $date = date('Y-m-d', strtotime('-1 year'));
        $avg_rating = $this->reviews()->where('is_approved', true)->where('rating', '>=', self::LIMITED_STOCK_COMPANY)->whereDate('created_at', '>=', $date)->average('rating');
        $rating = round($avg_rating, 1);
        return $rating;
    }

    /**
     * get store owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function owner()
    {
        return $this->hasOne(StoreStaff::class)->whereIsOwner(true);
    }

    /**
     * get store data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function store_data()
    {
        return $this->hasOne(StoreData::class)->approved();
    }

    /**
     * get store data
     *
     * @return HasMany
     */
    public function all_store_data()
    {
        return $this->hasMany(StoreData::class);
    }

    /**
     * get store profile translate data
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function store_profile_translate()
    {
        return $this->hasMany(StoreProfileTranslate::class,'store_id');
    }

    /**
     * get store profile translate data
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function store_profile_translates()
    {
        return $this->hasOne(StoreProfileTranslate::class,'store_id')->where('language_code', app()->getLocale());
    }

    /**
     * get store owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getViewRoute()
    {
        return route('shop', $this->slug);
    }

    public function getImageUrlAttribute()
    {
        if ($this->store_data && $this->store_data->banner) {
            return UtilityHelpers::getCdnUrl(route('resize', [1300, 270, $this->store_data->banner]));
        }

        return UtilityHelpers::getCdnUrl('assets/frontend/assets/img/seller.png');
    }

    public function resize_image_url($height = 1300, $width = 270)
    {
        $path = UtilityHelpers::getCdnUrl('assets/frontend/assets/img/seller.png');
        if ($this->store_data && $this->store_data->banner) {
            $path = UtilityHelpers::getCdnUrl(route('resize', [$height, $width, $this->store_data->banner]));
            // $file_headers = @get_headers($path);
            // && isset($file_headers[0]) &&   $file_headers[0] == "HTTP/1.0 404 Not Found"
            if( config('app.asset_url') == config('app.url') ) {
                $path = config('app.asset_url').'/storage/original/'.$this->store_data->banner;
            }
        }
        return $path;
    }


    public function resize_logo_url($height = 1300, $width = 270)
    {
        $path = UtilityHelpers::getCdnUrl('assets/frontend/assets/img/seller.png');
        if ($this->store_data && $this->store_data->logo) {
            $path = UtilityHelpers::getCdnUrl(route('resize', [$height, $width, $this->store_data->logo]));
            if( config('app.asset_url') == config('app.url') ) {
                $path = config('app.asset_url').'/storage/original/'.$this->store_data->logo;
            }
            return $path;
        }

        if ($this->store_data && $this->store_data->banner) {
            $path = UtilityHelpers::getCdnUrl(route('resize', [$height, $width, $this->store_data->banner]));
           if( config('app.asset_url') == config('app.url') ) {
                $path = config('app.asset_url').'/storage/original/'.$this->store_data->banner;
            }
            return $path;
        }

        return $path;
    }

    public function getDescriptionAttribute()
    {
        if ($this->store_data) {
            return $this->store_data->description;
        }

        return;
    }

    public function getReturnAndRefundsAttribute()
    {
        if ($this->store_data) {
            return $this->store_data->return_and_refunds;
        }

        return;
    }

    public function getPoliciesAttribute()
    {
        if ($this->store_data) {
            return $this->store_data->policies;
        }

        return;
    }

    public function hasSubMerchantKey()
    {
        if($this->submerchant_key) {
            return true;
        }
        return false;
    }

    public function isAbleToCreateMerchant()
    {
        if($this->legal_name && $this->email && $this->tax_office && $this->address && $this->iban ) {
            return true;
        }
        return false;
    }

    public function isAbleToApprove()
    {
        if (!$this->store_data) {
            return false;
        }

        if ($this->store_data->count() === 0) {
            return false;
        }
        if (empty($this->store_data->description)) {
            return false;
        }
        if (empty($this->address )) {
            return false;
        }
//        if (empty($this->return_address)) {
//            return false;
//        }
//        if ($this->return_address->count() === 0) {
//            return false;
//        }
        return true;
    }

    /**
     * @param $seperator
     * @return string
     */
    public function getApprovalValidationMessage($seperator = PHP_EOL) : string
    {
        $messages = [];
        if (!$this->hasStoreAddress()) {
            $messages[] =  __('Please setup your store profile and address.') ." <a href='".route("seller.store.store-detail")."'>Click Here</a>";
        }

//        if (!$this->hasReturnAddress()) {
//            $messages[] =  __('Please add store return address.')." <a href='".route("seller.store.store-detail")."'>Click Here</a>";
//        }

//        if ($this->all_store_data->count() === 0) {
//            $messages[] =  __('Please setup your store profile and address.')." <a href='".route("seller.store.store-detail")."'>click here</a>";
//        }

        if (!$this->hasStoreDescription()) {
            $messages[] =  __('Please add store description.')." <a href='".route("seller.store.profile")."'>click here</a>";
        }

        if  ($this->hasStoreDescription() && !$this->hasApprovedStoreDescription()) {
            $messages[] =  __('Your Store Profile is waiting for review.');
        }

        return implode($seperator, $messages);
    }

    /**
     * @return bool
     */
    private function hasStoreDescription() : bool
    {
        if ($this->all_store_data()->count() == 0){
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function hasApprovedStoreDescription() : bool
    {
        if (empty($this->store_data)) {
            return false;
        }

        /** @var StoreData $store_data */
        if (empty($this->store_data->description)) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function hasReturnAddress()
    {
        return !empty($this->return_address);
    }

    /**
     * @return bool
     */
    private function hasStoreAddress()
    {
        if (empty($this->address)) {
            return false;
        }
        if (empty($this->city)) {
            return false;
        }

        if (empty($this->country_id)) {
            return false;
        }

        return true;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_store');
    }

    public function isApproved()
    {
        if($this->is_approved == self::STATUS_APPROVED) {
            return true;
        }

        return false;
    }

    /**
     * get store approved reviews
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function approved_reviews()
    {
        return $this->hasMany(StoreReview::class)->where('is_approved', true);
    }
}
