<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Currency
 *
 * @property int $id
 * @property bool|null $status
 * @property string|null $is_default
 * @property string|null $title
 * @property string|null $base_rate
 * @property string|null $code
 * @property string|null $symbol
 * @property string|null $symbol_position
 * @property string|null $thousand_separator
 * @property string|null $decimal_separator
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CurrencyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereBaseRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereDecimalSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbolPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereThousandSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Currency extends Model
{

    const ID_DEFAULT = 1;
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true,
        'thousand_separator' => ',',
        'decimal_separator' => '.'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool',
    ];

    public function __toString()
    {
        return $this->code;
    }
}
