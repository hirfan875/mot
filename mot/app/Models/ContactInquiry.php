<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ContactInquiry
 *
 * @property int $id
 * @property int|null $status
 * @property bool $is_archive
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $subject
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry whereIsArchive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInquiry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContactInquiry extends Model
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
        'status' => ContactInquiry::NEW,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_archive' => 'bool',
    ];

    public function replies()
    {
        return $this->hasMany(ContactResponse::class, 'contact_inquiry_id');
    }
}
