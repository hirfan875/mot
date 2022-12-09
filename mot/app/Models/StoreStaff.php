<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordSeller;
use App\Notifications\VerifyEmailSeller;
use App\Traits\MediaHelpers;

/**
 * App\Models\StoreStaff
 *
 * @property int $id
 * @property bool|null $status
 * @property int $store_id
 * @property bool $is_owner
 * @property string|null $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Store $store
 * @method static \Database\Factories\StoreStaffFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStaff whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreStaff extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, MediaHelpers;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'bool',
        'is_owner' => 'bool',
    ];

    /**
     * Get the store that owns the staff.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * get vendor products
     *
     * @return \Illuminate\Support\Collection
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailSeller());
    }

    /**
     * Send a password reset email to the user
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordSeller($token));
    }

    /**
     * overrides Authenticatable method
     */
//    public function getAuthIdentifier()
//    {
//        return $this->email;
//    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
//    public function getAuthIdentifierName()
//    {
//        return 'email';
//    }
}
