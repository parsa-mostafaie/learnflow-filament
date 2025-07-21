<?php

namespace App\Filament\Actions;

use App\Enums\Status;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;

/**
 * ApproveSimpleAction is a simple custom action for Filament Tables
 * that allows authorized users to approve a single record at a time.
 * 
 * @package \App\Filament\Actions
 * 
 * @see \App\Filament\Actions\ApproveAction
 * @see \App\Filament\Actions\ApproveBulkAction
 */
class ApproveSimpleAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Get the default name of the action.
     *
     * @return string|null
     */
    public static function getDefaultName(): ?string
    {
        return 'approve';
    }

    /**
     * Set up the action.
     *
     * @return void
     */
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
