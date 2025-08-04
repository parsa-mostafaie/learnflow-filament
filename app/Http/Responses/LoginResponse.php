<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Filament\Notifications\Notification;
use Illuminate\Routing\Redirector;

class LoginResponse implements Responsable
{
  public function toResponse($request): RedirectResponse|Redirector
  {
    Notification::make()
      ->title(__('messages.login'))
      ->success()
      ->duration(3000)
      ->send();

    return redirect()->intended(Filament::getUrl());
  }
}