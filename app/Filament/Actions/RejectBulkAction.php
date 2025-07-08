<?php

namespace App\Filament\Actions;

use App\Enums\Status;
use Filament\Tables\Actions\BulkAction;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RejectBulkAction extends BulkAction
{
  use CanCustomizeProcess;

  public static function getDefaultName(): ?string
  {
    return 'reject';
  }

  protected function setUp(): void
  {
    parent::setUp();

    $this->label(__('filament-actions.reject.multiple.label'));

    $this->modalHeading(fn(): string => __('filament-actions.reject.multiple.modal.heading', [
      'label' => $this->getPluralModelLabel(),
    ]));

    $this->modalSubmitActionLabel(__('filament-actions.reject.multiple.modal.actions.reject.label'));

    $this->successNotificationTitle(__('filament-actions.reject.multiple.notifications.rejected.title'));

    $this->defaultColor('danger');

    $this->icon(FilamentIcon::resolve('actions::reject-action') ?? 'heroicon-m-x-circle');

    $this->requiresConfirmation();

    $this->modalIcon(FilamentIcon::resolve('actions::reject-action.modal') ?? 'heroicon-o-x-circle');

    $this->authorize('rejectAny');

    $this->action(function (): void {
      $this->process(function (Collection $records) {
        $records->each(function (Model $record) {
          if (!$record->isStatus(Status::Rejected)) {
            $record->setStatus(Status::Rejected);
          }
        });
      });

      $this->success();
    });

    $this->deselectRecordsAfterCompletion();
  }
}
