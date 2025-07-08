<?php

namespace App\Filament\Actions;

use App\Enums\Status;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;

class ApproveSimpleAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'approve';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-actions.approve.single.label'));

        $this->modalHeading(fn(): string => __('filament-actions.approve.single.modal.heading', [
            'label' => $this->getRecordTitle(),
        ]));

        $this->modalSubmitActionLabel(__('filament-actions.approve.single.modal.actions.approve.label'));

        $this->successNotificationTitle(__('filament-actions.approve.single.notifications.approved.title'));

        $this->color('success');

        $this->groupedIcon(FilamentIcon::resolve('actions::approve-action.grouped') ?? 'heroicon-m-check-circle');

        $this->icon('heroicon-m-check-circle');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::approve-action.modal') ?? 'heroicon-o-check');

        $this->keyBindings(['mod+a']);

        $this->hidden(static function (Model $record): bool {
            return $record->isStatus(Status::Approved);
        });

        $this->authorize(function (Model $record): bool {
            return auth()->user()?->can('approve', $record);
        });

        $this->action(function (): void {
            $result = $this->process(function (Model $record) {
                return $record->setStatus(Status::Approved);
            });

            if (!$result) {
                $this->failure();
                return;
            }

            $this->success();
        });
    }
}
