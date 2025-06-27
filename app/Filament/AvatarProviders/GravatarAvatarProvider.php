<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravolt\Avatar\Facade as Avatar;

class GravatarAvatarProvider implements Contracts\AvatarProvider
{
  public function get(Model|Authenticatable $record): string
  {
    // d=mp|identicon|monsterid|wavatar|retro|robohash|blank

    $email = $record->email;
    $email = strtolower(trim($email));
    $hash = md5($email);
    $dOptions = ['identicon', 'monsterid', 'wavatar', 'robohash', 'retro'];
    $sub = substr($hash, 0, 4);
    $index = hexdec($sub) % count($dOptions);
    $d = $dOptions[$index];

    return Avatar::create($email)->toGravatar(['d' => $d, 'r' => 'g', 's' => 100]);
  }
}