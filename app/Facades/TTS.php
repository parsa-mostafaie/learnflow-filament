<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class TTS
 * 
 * @method static string generate(string $text, ?string $lang = null, ?string $voice = null)
 * @method static array voices(string $lang)
 *
 * @see \App\Services\Interfaces\TTS
 * @see \App\Services\TTS
 */
class TTS extends Facade
{
  /**
   * Get the registered name of the component.
   * 
   * This method returns the service container binding that the facade
   * will resolve to when methods are called on it.
   * 
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    // Returns the name of the service container binding for the TTS service
    return 'tts';
  }
}
