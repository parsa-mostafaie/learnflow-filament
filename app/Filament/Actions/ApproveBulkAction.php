<?php

namespace App\Filament\Actions;

use App\Enums\Status;
use Filament\Tables\Actions\BulkAction;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * ApproveBulkAction is a custom bulk action for Filament Tables that allows authorized users to approve records.
 * (Many records each time the action is executed.)
 *
 * @package \App\Filament\Actions
 * 
 * @see \App\Filament\Actions\ApproveSimpleAction
 * @see \App\Filament\Actions\ApproveAction
 */
class ApproveBulkAction extends BulkAction
{
  use CanCustomizeProcess;

  /**
   * Get the default name of the action.
   * 
   * Will be used to differentiate from other bulk actions.
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

    $this->label(__('filament-actions.approve.multiple.label'));

    $this->modalHeading(fn(): string => __('filament-actions.approve.multiple.modal.heading', [
      'label' => $this->getPluralModelLabel(),
    ]));

    $this->modalSubmitActionLabel(__('filament-actions.approve.multiple.modal.actions.approve.label'));

    $this->successNotificationTitle(__('filament-actions.approve.multiple.notifications.approved.title'));

    /**
     * Set the default color of the action.
     *
     * @var string
     */
    $this->defaultColor('success');

    /**
     * Set the icon of the action.
     *
     * @see \Filament\Support\Facades\FilamentIcon::resolve()
     */
    $this->icon(FilamentIcon::resolve('actions::approve-action') ?? 'heroicon-m-check-circle');

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

    $this->authorize('approveAny');

    /**
     * Perform the action.
     * 
     * Will change status of records to "approved"
     *
     * @return void
     */
    $this->action(function (): void {
      $this->process(function (Collection $records) {
        $records->each(function (Model $record) {
          if (!$record->isStatus(Status::Approved)) {
            $record->setStatus(Status::Approved);
          }
        });
      });

      $this->success();
    });

    $this->deselectRecordsAfterCompletion();
  }
}
