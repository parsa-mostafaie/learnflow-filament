<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EnrollToggleAction extends Action
{
  use CanCustomizeProcess;

  public static function getDefaultName(): ?string
  {
    return 'toggleEnroll';
  }

  protected function setUp(): void
  {
    parent::setUp();

    $this->label(
      fn(Model $record): string =>
      $this->isEnrolled($record)
      ? __('filament-actions.enroll.unenroll.label')
      : __('filament-actions.enroll.single.label')
    );

    $this->modalHeading(
      fn(Model $record): string =>
      $this->isEnrolled($record)
      ? __('filament-actions.enroll.unenroll.modal.heading', ['label' => $this->getRecordTitle()])
      : __('filament-actions.enroll.single.modal.heading', ['label' => $this->getRecordTitle()])
    );

    $this->modalSubmitActionLabel(
      fn(Model $record): string =>
      $this->isEnrolled($record)
      ? __('filament-actions.enroll.unenroll.modal.actions.unenroll.label')
      : __('filament-actions.enroll.single.modal.actions.enroll.label')
    );

    $this->successNotificationTitle(
      fn(Model $record): string =>
      !$this->isEnrolled($record)
      ? __('filament-actions.enroll.unenroll.notifications.unenrolled.title')
      : __('filament-actions.enroll.single.notifications.enrolled.title')
    );

    $this->defaultColor(
      fn(Model $record): string =>
      $this->isEnrolled($record) ? 'danger' : 'success'
    );

    $this->icon(
      fn(Model $record): string =>
      $this->isEnrolled($record) ? 'heroicon-m-user-minus' : 'heroicon-m-user-plus'
    );

    $this->groupedIcon(FilamentIcon::resolve('actions::enroll-action.grouped') ?? 'heroicon-m-user-plus');

    $this->modalIcon(
      fn(Model $record): string =>
      $this->isEnrolled($record) ? 'heroicon-o-user-minus' : 'heroicon-o-user-plus'
    );

    $this->keyBindings(['mod+e']);

    $this->requiresConfirmation();

    $this->authorize(
      fn(Model $record): bool =>
      Auth::user()?->can('enroll', $record)
    );

    $this->hidden(
      fn(Model $record): bool =>
      !Auth::user()?->can('enroll', $record)
    );

    $this->action(function () {
      $record = $this->getRecord();
      $user = Auth::user();

      if (!$user) {
        $this->failure();
        return;
      }

      $result = $this->process(function () use ($record, $user) {
        return $this->isEnrolled($record)
          ? $record->unenroll($user)
          : $record->enroll($user);
      });

      if (!$result) {
        $this->failure();
        return;
      }

      $this->success();
      $this->record($record->fresh());
    });
  }

  protected function isEnrolled(Model $record): bool
  {
    return method_exists($record, 'isEnrolledBy') && $record->isEnrolledBy(Auth::user());
  }
}
