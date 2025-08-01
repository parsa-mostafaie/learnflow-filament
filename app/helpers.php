<?php

use Filament\Facades\Filament;
use Illuminate\Support\Number;

//? Numbers
if (!function_exists('forhumans')) {
    function forhumans(int|float $number, int $precision = 0, ?int $maxPrecision = 2, bool $abbreviate = true)
    {
        return $number == 0 ? Number::format(0, $precision, $maxPrecision) : Number::forHumans($number, $precision, $maxPrecision, $abbreviate);
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
use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Cloud\TextToSpeech\V1\AudioConfig;

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