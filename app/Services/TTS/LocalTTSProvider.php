<?php

namespace App\Services\TTS;

use App\Helpers\LangHelper;
use Illuminate\Support\Facades\Http;

class LocalTTSProvider implements TTSProviderInterface
{
  protected string $url;
  protected array $voices;

  public function __construct(array $config)
  {
    $this->url = $config['url'];
    $this->voices = $config['voices'] ?? [];
  }

  public function synthesize(string $text, string $lang, ?string $voice = null): string
  {
    if (!isset($this->voices[$lang])) {
      throw new \InvalidArgumentException("No voices for lang [$lang] in LocalTTS");
    }

    $voice ??= LangHelper::defaultVoice($this->voices($lang));
    $payload = ['text' => $text, 'voice' => $voice];

    $response = Http::post($this->url, $payload);

    if (!$response->successful()) {
      throw new \RuntimeException("Local TTS failed: " . $response->body());
    }

    return $response->body();
  }

  public function voices(string $lang): array
  {
    return $this->voices[$lang]['options'] ?? [];
  }
}
