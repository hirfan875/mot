<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SponsorSection
 *
 * @property int $id
 * @property bool|null $status
 * @property string|null $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SponsorCategory[] $categories
 * @property-read int|null $categories_count
 * @property-read mixed $edit_url
 * @property-read \App\Models\HomepageSorting|null $sort
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorSection query()
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorSection whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorSection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SponsorSection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SponsorSection extends Model
{
    use HasFactory;

    protected $guarded = [];

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
     * get section categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(SponsorCategory::class);
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
        return route('admin.sponsored.categories.edit', ['item' => $this->id]);
    }
}
