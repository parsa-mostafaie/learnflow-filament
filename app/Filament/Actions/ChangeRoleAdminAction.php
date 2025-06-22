<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use App\Events\UserRoleChanged;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ChangeRoleAdminAction extends ChangeRoleAction
{
  use CanCustomizeProcess;

  public static function getDefaultName(): ?string
  {
    return 'change-role-admin';
  }

  protected function setUp(): void
  {
    parent::setUp();
  }

  protected function getNextRole(): ?string
  {
    // Logic to determine the next role based on the current role or other criteria
    return 'admin';
  }
}