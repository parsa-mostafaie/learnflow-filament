<?php
namespace App\Services\Interfaces;

interface TTS
{
  public function generate(string $text, ?string $lang = null, ?string $voice = null): string;
  public function voices(string $lang): array;
}