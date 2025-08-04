<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Filament\Notifications\Notification;

class LogoutResponse implements Responsable
{
  public function toResponse($request): RedirectResponse
  {
    Notification::make()
      ->title(__('messages.logout'))
      ->danger()
      ->duration(3000)
      ->send();

    return redirect()->route('welcome');
  }
}