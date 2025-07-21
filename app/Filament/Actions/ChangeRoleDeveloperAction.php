<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use App\Events\UserRoleChanged;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * ChangeRoleUserAction extends ChangeRoleAction to specifically change role to developer.
 * 
 * @see \App\Filament\Actions\ChangeRoleAction
 * 
 * @package \App\Filament\Actions
 */
class ChangeRoleDeveloperAction extends ChangeRoleAction
{
  use CanCustomizeProcess;

  /**
   * Get the default name of the action.
   *
   * @return string|null
   */
  public static function getDefaultName(): ?string
  {
    return 'change-role-developer';
  }

  /**
   * Set up the action by calling the parent setUp method.
   *
   * @return void
   */
  protected function setUp(): void
  {
    parent::setUp();
  }

  /**
   * Get the next role for the user.
   *
   * @return string|null Next role as string or null if undefined
   */
  protected function getNextRole(): ?string
  {
    return 'developer';
  }
}