<?php

namespace App\Service;

use App\Models\Currency;

class CurrencyService
{
    /**
     * create new currency
     *
     * @param array $request
     * @return Currency
     */
    public function create(array $request): Currency
    {
        $currency = new Currency();

        // set this currency as default
        if ( isset($request['is_default']) ) {
            $currency->is_default = $request['is_default'];
        }

        $currency->title = $request['title'];
        $currency->base_rate = $request['base_rate'];
        $currency->code = $request['code'];
        $currency->symbol = $request['symbol'];
        $currency->symbol_position = $request['symbol_position'];
        $currency->thousand_separator = $request['thousand_separator'];
        $currency->decimal_separator = $request['decimal_separator'];
        $currency->emoji = $request['emoji'];
        $currency->emoji_uc = $request['emoji_uc'];

        $currency->save();

        return $currency;
    }

    /**
     * update currency
     *
     * @param Currency $currency
     * @param array $request
     * @return Currency
     */
    public function update(Currency $currency, array $request): Currency
    {
        $currency->title = $request['title'];
        $currency->base_rate = $request['base_rate'];
        $currency->code = $request['code'];
        $currency->symbol = $request['symbol'];
        $currency->symbol_position = $request['symbol_position'];
        $currency->thousand_separator = $request['thousand_separator'];
        $currency->decimal_separator = $request['decimal_separator'];
        $currency->emoji = $request['emoji'];
        $currency->emoji_uc = $request['emoji_uc'];

        $currency->save();

        return $currency;
    }

    public function getAll()
    {
        return Currency::where('status', true)->get();
    }
}
