<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreQuestion
 *
 * @property int $id
 * @property int $store_id
 * @property int $status
 * @property bool $is_archive
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion whereIsArchive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreQuestion extends Model
{
    use HasFactory;

    // for status
    const NEW = 1;
    const VIEWED = 2;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => StoreQuestion::NEW,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_archive' => 'bool',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['store_id', 'name', 'email', 'phone', 'message'];

    /**
     * get store that own the question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function replies()
    {
        return $this->hasMany(StoreQuestionReply::class, 'store_question_id');
    }
}
