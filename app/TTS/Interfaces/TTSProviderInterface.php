<?php

namespace App\TTS\Interfaces;

interface TTSProviderInterface
{
  /**
   * Generate speech binary
   */
  public function synthesize(string $text, string $lang, ?string $voice = null): string;

  /**
   * Get available voices for a language
   */
  public function voices(string $lang): array;
}
