<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

/**
 * ApproveAction is a custom action for Filament Tables that allows authorized users to approve records. (One record each time the action is executed.)
 *
 * @package \App\Filament\Actions
 * @see \App\Filament\Actions\ApproveBulkAction
 */
class ApproveAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Get the default name of the action. 
     * 
     * Will be used to differentiate from other actions.
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

        /**
         * Set the default color of the action.
         *
         * @var string
         */
        $this->defaultColor('success');

        /**
         * Set the grouped icon of the action.
         *
         * @see \Filament\Support\Facades\FilamentIcon::resolve()
         */
        $this->groupedIcon(FilamentIcon::resolve('actions::approve-action.grouped') ?? 'heroicon-m-check-circle');

        /**
         * Set the icon of the action.
         *
         * @var string
         */
        $this->icon('heroicon-m-check-circle');

        /**
         * Require confirmation before performing the action.
         */
        $this->requiresConfirmation();

        /**
         * Set the modal icon of the action.
         *
         * @see \Filament\Support\Facades\FilamentIcon::resolve()
         */
        $this->modalIcon(FilamentIcon::resolve('actions::approve-action.modal') ?? 'heroicon-o-check');

        /**
         * Add key bindings for the action.
         * 
         * Defines keyboard shortcut to call the action.
         *
         * @var array
         */
        $this->keyBindings(['mod+a']);

        /**
         * Hide the action if the record is already in an approved status.
         *
         * @param \Illuminate\Database\Eloquent\Model $record
         * @return bool
         */
        $this->hidden(static function (Model $record): bool {
            return $record->isStatus('approved');
        });

        /**
         * Authorize the action based on the user's permissions.
         *
         * @param \Illuminate\Database\Eloquent\Model $record
         * @return bool
         */
        $this->authorize(function (Model $record): bool {
            return auth()->user()?->can('approve', $record);
        });

        /**
         * Perform the action.
         * 
         * Will Change status of record to "approved"
         */
        $this->action(function (): void {
            $result = $this->process(function (Model $record) {
                return $record->setStatus('approved');
            });

            if (!$result) {
                $this->failure();
                return;
            }

            $this->success();
        });
    }
}
