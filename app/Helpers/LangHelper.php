<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class LangHelper
{
  public static function detectLang(string $text): string
  {
    $result = langof($text); // e.g. ["fa" => 0.84]

    return array_keys($result)[0];
  }

  public static function browserTTS(): bool
  {
    return Config::boolean('tts.browser');
  }

  public static function randomVoice(array $voices): string
  {
    return $voices[array_rand($voices)];
  }

  public static function defaultVoice(array $voices): string
  {
    return $voices['default'] ?? $voices[array_key_first($voices)];
  }
}
