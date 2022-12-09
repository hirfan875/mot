<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaHelpers;

/**
 * App\Models\Language
 *
 * @property int $id
 * @property bool|null $status
 * @property string|null $is_default
 * @property string|null $title
 * @property string|null $code
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $native
 * @property string|null $direction
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Translation[] $translation
 * @property-read int|null $translation_count
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereNative($value)
 */
class Language extends Model
{
    use HasFactory, MediaHelpers;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true
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
     * get translation
     *
     * @return \Illuminate\Support\Collection
     */
    public function translation()
    {
        return $this->hasMany(Translation::class);
    }
}
