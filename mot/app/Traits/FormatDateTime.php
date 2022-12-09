<?php

namespace App\Traits;

trait FormatDateTime
{
    public function getCreatedAtAttribute($value)
    {
        return $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value;
    }
}