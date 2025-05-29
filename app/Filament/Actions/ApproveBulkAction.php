<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\BulkAction;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ApproveBulkAction extends BulkAction
{
  use CanCustomizeProcess;

  public static function getDefaultName(): ?string
  {
    return 'approve';
  }

  protected function setUp(): void
  {
    parent::setUp();

    $this->label(__('filament-actions.approve.multiple.label'));

    $this->modalHeading(fn(): string => __('filament-actions.approve.multiple.modal.heading', [
      'label' => $this->getPluralModelLabel(),
    ]));

    $this->modalSubmitActionLabel(__('filament-actions.approve.multiple.modal.actions.approve.label'));

    $this->successNotificationTitle(__('filament-actions.approve.multiple.notifications.approved.title'));

    $this->defaultColor('success');

    $this->icon(FilamentIcon::resolve('actions::approve-action') ?? 'heroicon-m-check-circle');

    $this->requiresConfirmation();

    $this->modalIcon(FilamentIcon::resolve('actions::approve-action.modal') ?? 'heroicon-o-check');

    $this->action(function (): void {
      $this->process(function (Collection $records) {
        $records->each(function (Model $record) {
          if (!$record->isStatus('approved')) {
            $record->setStatus('approved');
          }
        });
      });

      $this->success();
    });

    $this->deselectRecordsAfterCompletion();
  }
}
