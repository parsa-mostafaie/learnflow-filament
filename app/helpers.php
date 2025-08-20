<?php

use Filament\Facades\Filament;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Filament\Support\Colors\Color;

//? Numbers
if (!function_exists('forhumans')) {
    function forhumans(int|float $number, int $precision = 0, ?int $maxPrecision = 2, bool $abbreviate = true)
    {
        return $number == 0 ? Number::format(0, $precision, $maxPrecision) : Number::forHumans($number, $precision, $maxPrecision, $abbreviate);
    }
}

if (!function_exists('changeLabel')) {
    function changeLabel(float $percent): string
    {
        $key = $percent >= 0 ? 'increase' : 'decrease';

        $formattedValue = Number::percentage(abs($percent), precision: 2);

        return __("messages.$key", ['value' => $formattedValue]);
    }
}

if (!function_exists('changeColor')) {
    function changeColor(float $percent): array
    {
        return $percent > 0
            ? Color::Emerald
            : ($percent < 0 ? Color::Rose : Color::Gray);
    }
}

if (!function_exists('changeIcon')) {
    function changeIcon(float $percent): string
    {
        return $percent > 0 ? "heroicon-m-arrow-trending-up" : "heroicon-m-arrow-trending-down";
    }
}

if (!function_exists('changeInfo')) {
    function changeInfo(Collection $collection): array
    {
        $last = $collection->last();
        $prev = $collection->count() >= 2
            ? $collection->slice(-2, 1)->first()
            : null;

        $percentage = $prev && $prev != 0
            ? (($last - $prev) / $prev) * 100
            : ($last > 0 ? 100 : 0);

        $color = changeColor($percentage);

        $icon = $percentage ? changeIcon($percentage) : null;

        $description = $percentage ? changeLabel($percentage) : null;

        return [
            'percentage' => $percentage,
            'color' => $color,
            'icon' => $icon,
            'description' => $description,
        ];
    }
}

//? filament authentication
if (!function_exists('login_url')) {

    /**
     * @param  array<mixed>  $parameters
     */
    function login_url(array $parameters = []): ?string
    {
        return Filament::getCurrentPanel()->getLoginUrl($parameters);
    }
}

if (!function_exists('register_url')) {

    /**
     * @param  array<mixed>  $parameters
     */
    function register_url(array $parameters = []): ?string
    {
        return Filament::getCurrentPanel()->getRegistrationUrl($parameters);
    }
}

//? Date and time Localization
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

use LanguageDetection\Language;

//? Ai features
if (!function_exists('langof')) {
    function langof($string)
    {
        $ld = new Language(['fa', 'en', 'ar']);

        $ld->setMaxNgrams(9000);

        $language = $ld->detect($string)->bestResults()->close();

        return $language;
    }
}