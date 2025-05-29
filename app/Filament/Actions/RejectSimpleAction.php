<?php

namespace App\Filament\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;

class RejectSimpleAction extends Action
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

        $this->color('danger');

        $this->groupedIcon(FilamentIcon::resolve('actions::reject-action.grouped') ?? 'heroicon-m-x-circle');

        $this->icon('heroicon-m-x-circle');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::reject-action.modal') ?? 'heroicon-o-x-circle');

        $this->keyBindings(['mod+r']);

        $this->hidden(fn(Model $record): bool => $record->isStatus('rejected'));

        $this->authorize(fn(Model $record): bool => auth()->user()?->can('reject', $record));

        $this->action(function (): void {
            $result = $this->process(fn(Model $record) => $record->setStatus('rejected'));

            $result ? $this->success() : $this->failure();
        });
    }
}
