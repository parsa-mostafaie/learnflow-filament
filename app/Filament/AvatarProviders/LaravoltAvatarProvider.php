<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravolt\Avatar\Facade as Avatar;

class LaravoltAvatarProvider implements Contracts\AvatarProvider
{
  public function get(Model|Authenticatable $record): string
  {
    return Avatar::create(Filament::getNameForDefaultAvatar($record))->setFont(\resource_path('/fonts/Vazirmatn.ttf'))->toBase64();
  }
}