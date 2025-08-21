<?php

namespace App\Services;

use App\Helpers\LangHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\TTS\Interfaces\TTSProviderInterface;
use App\Services\Interfaces\TTS as ITTS;

class TTS implements ITTS
{
  protected TTSProviderInterface $provider;

  public function __construct(?string $providerName = null)
  {
    $providerName ??= config('tts.default');
    $config = config("tts.providers.$providerName");

    if (!empty($config) && is_array($config) && !empty($config['provider'])) {
      $this->provider = app($config['provider'], ['config' => $config]);
    } else {
      throw new \InvalidArgumentException("Unsupported TTS provider [$providerName]");
    }
  }

  /**
   * Generate speech file and cache
   */
  public function generate(string $text, ?string $lang = null, ?string $voice = null): string
  {
    $lang ??= LangHelper::detectLang($text);
    $voice ??= LangHelper::defaultVoice($this->provider->voices($lang));

    $payload = compact('text', 'lang', 'voice');
    $cacheKey = 'tts_' . md5(json_encode($payload));

    return Cache::rememberForever($cacheKey, function () use ($text, $lang, $voice, $cacheKey) {
      $binary = $this->provider->synthesize($text, $lang, $voice);

      $path = config('tts.path') . '/' . $cacheKey . '.wav';
      Storage::disk(config('tts.disk'))->put($path, $binary);

      return Storage::disk(config('tts.disk'))->url($path);
    });
  }

  /**
   * Get voices for current provider
   */
  public function voices(string $lang): array
  {
    return $this->provider->voices($lang);
  }
}
