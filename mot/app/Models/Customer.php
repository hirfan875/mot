<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordCustomer;
use App\Notifications\VerifyEmailCustomer;
// sanctum
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property bool|null $status
 * @property string|null $name
 * @property string|null $username
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $birthday
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CustomerAddress[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\CustomerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUsername($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductReview[] $product_reviews
 * @property-read int|null $product_reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StoreReview[] $store_reviews
 * @property-read int|null $store_reviews_count
 * @property string|null $identity_number
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereIdentityNumber($value)
 */
class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const TYPEGUEST = 1;
    const TYPEACCOUNT = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'name', 'username', 'email', 'email_verified_at', 'password', 'phone', 'birthday', 'is_guest', 'image', 'uid', 'provider', 'remember_token'
    ];

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
    ];

    /**
     * get customer addresses
     *
     * @return \Illuminate\Support\Collection
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustomer());
    }

    /**
     * Send a password reset email to the user
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordCustomer($token));
    }

    public function product_reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function store_reviews()
    {
        return $this->hasMany(StoreReview::class);
    }

    /**
     * overrides Authenticatable method
     */
    public function getAuthIdentifier()
    {
        return $this->email;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'email';
    }
}
