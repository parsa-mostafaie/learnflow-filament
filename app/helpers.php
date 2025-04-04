<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

function forhumans(int|float $number, int $precision = 0, ?int $maxPrecision = 2, bool $abbreviate = true)
{
    return Number::forHumans($number, $precision, $maxPrecision, $abbreviate);
}