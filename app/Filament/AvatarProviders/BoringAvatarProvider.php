<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class BoringAvatarProvider implements Contracts\AvatarProvider
{
  public function get(Model|Authenticatable $record): string
  {
    $name = str(Filament::getNameForDefaultAvatar($record))
      ->trim()
      ->explode(' ')
      ->map(fn(string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
      ->join(' ');

    return "https://source.boringavatars.com/beam/120/" . urlencode($name);
  }
}