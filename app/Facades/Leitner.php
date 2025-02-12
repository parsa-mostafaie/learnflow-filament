<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Leitner
 * 
 * This is the facade for the Leitner service.
 * 
 * The facade provides a static interface to the underlying Leitner service,
 * which is responsible for implementing the actual functionality.
 * 
 * @see \App\Services\Leitner
 */
class Leitner extends Facade
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
    // Returns the name of the service container binding for the Leitner service
    return \App\Services\Leitner::class;
  }
}
