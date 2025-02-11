<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Leitner extends Facade
{
  protected static function getFacadeAccessor()
  {
    return \App\Services\Leitner::class;
  }
}
