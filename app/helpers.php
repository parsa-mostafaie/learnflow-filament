<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

function forhumans(int|float $number, int $precision = 0, ?int $maxPrecision = 2, bool $abbreviate = true)
{
    return Number::forHumans($number, $precision, $maxPrecision, $abbreviate);
}

function login_url()
{
    return Filament::getCurrentPanel()->getLoginUrl();
}

function register_url()
{
    return Filament::getCurrentPanel()->getRegistrationUrl();
}

if (!function_exists('is_jalali_supported')) {
    /**
     * Determine if the current locale supports the Jalali calendar.
     *
     * @return bool True if the locale is in the supported list, false otherwise.
     */
    function is_jalali_supported()
    {
        $jalali_locales = ['fa']; // List of locales that support Jalali

        // Ensure App::isLocale exists before calling
        if (function_exists('app')) {
            return in_array(app()->getLocale(), $jalali_locales, true);
        }

        return false;
    }
}