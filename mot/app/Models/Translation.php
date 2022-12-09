<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Translation
 *
 * @property int $id
 * @property int|null $language_id
 * @property string|null $key
 * @property string|null $translate
 * @property bool|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Language|null $language
 * @method static \Database\Factories\TranslationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereTranslate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Translation extends Model {

    use HasFactory;
    
    
    protected $fillable = ['key','translate'];

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
     * get language
     *
     * @return Language
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
