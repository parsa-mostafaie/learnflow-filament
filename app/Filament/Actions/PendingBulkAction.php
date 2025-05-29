<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\BulkAction;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PendingBulkAction extends BulkAction
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'pending';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-actions.pending.multiple.label'));

        $this->modalHeading(fn(): string => __('filament-actions.pending.multiple.modal.heading', [
            'label' => $this->getPluralModelLabel(),
        ]));

        $this->modalSubmitActionLabel(__('filament-actions.pending.multiple.modal.actions.pending.label'));

        $this->successNotificationTitle(__('filament-actions.pending.multiple.notifications.pending.title'));

        $this->defaultColor('warning');

        $this->icon(FilamentIcon::resolve('actions::pending-action') ?? 'heroicon-m-clock');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::pending-action.modal') ?? 'heroicon-o-clock');

        $this->action(function (): void {
            $this->process(function (Collection $records) {
                $records->each(function (Model $record) {
                    if (!$record->isStatus('pending')) {
                        $record->setStatus('pending');
                    }
                });
            });

            $this->success();
        });

        $this->deselectRecordsAfterCompletion();
    }
}
