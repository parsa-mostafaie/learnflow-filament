<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class PendingAction extends Action
{
  use CanCustomizeProcess;

  public static function getDefaultName(): ?string
  {
    return 'pending';
  }

  protected function setUp(): void
  {
    parent::setUp();

    $this->label(__('filament-actions.pending.single.label'));

    $this->modalHeading(fn(): string => __('filament-actions.pending.single.modal.heading', [
      'label' => $this->getRecordTitle(),
    ]));

    $this->modalSubmitActionLabel(__('filament-actions.pending.single.modal.actions.pending.label'));

    $this->successNotificationTitle(__('filament-actions.pending.single.notifications.pended.title'));

    $this->defaultColor('warning');

    $this->groupedIcon(FilamentIcon::resolve('actions::pending-action.grouped') ?? 'heroicon-m-clock');

    $this->icon('heroicon-m-clock');

    $this->requiresConfirmation();

    $this->modalIcon(FilamentIcon::resolve('actions::pending-action.modal') ?? 'heroicon-o-clock');

    $this->keyBindings(['mod+p']);

    $this->hidden(static function (Model $record): bool {
      return $record->isStatus('pending');
    });

    $this->authorize(function (Model $record): bool {
      return auth()->user()?->can('pending', $record);
    });

    $this->action(function (): void {
      $result = $this->process(function (Model $record) {
        return $record->setStatus('pending');
      });

      if (!$result) {
        $this->failure();
        return;
      }

      $this->success();
    });
  }
}
