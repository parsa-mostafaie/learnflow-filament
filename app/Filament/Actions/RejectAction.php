<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class RejectAction extends Action
{
  use CanCustomizeProcess;

  public static function getDefaultName(): ?string
  {
    return 'reject';
  }

  protected function setUp(): void
  {
    parent::setUp();

    $this->label(__('filament-actions.reject.single.label'));

    $this->modalHeading(fn(): string => __('filament-actions.reject.single.modal.heading', [
      'label' => $this->getRecordTitle(),
    ]));

    $this->modalSubmitActionLabel(__('filament-actions.reject.single.modal.actions.reject.label'));

    $this->successNotificationTitle(__('filament-actions.reject.single.notifications.rejected.title'));

    $this->defaultColor('danger');

    $this->groupedIcon(FilamentIcon::resolve('actions::reject-action.grouped') ?? 'heroicon-m-x-circle');

    $this->icon('heroicon-m-x-circle');

    $this->requiresConfirmation();

    $this->modalIcon(FilamentIcon::resolve('actions::reject-action.modal') ?? 'heroicon-o-x-circle');

    $this->keyBindings(['mod+r']);

    $this->hidden(static function (Model $record): bool {
      return $record->isStatus('rejected');
    });

    $this->authorize(function (Model $record): bool {
      return auth()->user()?->can('reject', $record);
    });

    $this->action(function (): void {
      $result = $this->process(function (Model $record) {
        return $record->setStatus('rejected');
      });

      if (!$result) {
        $this->failure();
        return;
      }

      $this->success();
    });
  }
}
