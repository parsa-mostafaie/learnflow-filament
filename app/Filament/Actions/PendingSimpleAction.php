<?php

namespace App\Filament\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;

class PendingSimpleAction extends Action
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

        $this->color('warning');

        $this->groupedIcon(FilamentIcon::resolve('actions::pending-action.grouped') ?? 'heroicon-m-clock');

        $this->icon('heroicon-m-clock');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::pending-action.modal') ?? 'heroicon-o-clock');

        $this->keyBindings(['mod+p']);

        $this->hidden(fn(Model $record): bool => $record->isStatus('pending'));

        $this->authorize(fn(Model $record): bool => auth()->user()?->can('pending', $record));

        $this->action(function (): void {
            $result = $this->process(fn(Model $record) => $record->setStatus('pending'));

            $result ? $this->success() : $this->failure();
        });
    }
}
