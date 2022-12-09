<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\HomepageSorting
 *
 * @property int $id
 * @property int|null $sort_order
 * @property string $sortable_type
 * @property int $sortable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $sortable
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageSorting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageSorting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageSorting query()
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageSorting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageSorting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageSorting whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageSorting whereSortableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageSorting whereSortableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageSorting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HomepageSorting extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the parent sortable model
     */
    public function sortable()
    {
        return $this->morphTo();
    }
}
