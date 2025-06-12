<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use App\Events\CourseEnrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EnrollToggleSimpleAction extends Action
{
  use CanCustomizeProcess;

  public static function getDefaultName(): ?string
  {
    return 'toggleEnroll';
  }

  protected function setUp(): void
  {
    parent::setUp();

    $this->label(function (): string {
      return $this->isEnrolled()
        ? __('filament-actions.enroll.unenroll.label')
        : __('filament-actions.enroll.single.label');
    });

    $this->modalHeading(function ($record): string {
      return $this->isEnrolled()
        ? __('filament-actions.enroll.unenroll.modal.heading', ['label' => $record->title])
        : __('filament-actions.enroll.single.modal.heading', ['label' => $record->title]);
    });

    $this->modalSubmitActionLabel(function (): string {
      return $this->isEnrolled()
        ? __('filament-actions.enroll.unenroll.modal.actions.unenroll.label')
        : __('filament-actions.enroll.single.modal.actions.enroll.label');
    });

    $this->successNotificationTitle(function (): string {
      return !$this->isEnrolled()
        ? __('filament-actions.enroll.unenroll.notifications.unenrolled.title')
        : __('filament-actions.enroll.single.notifications.enrolled.title');
    });

    $this->color(fn(): string => $this->isEnrolled() ? 'danger' : 'success');

    $this->icon(fn(): string => $this->isEnrolled() ? 'heroicon-m-user-minus' : 'heroicon-m-user-plus');

    $this->groupedIcon(fn() => FilamentIcon::resolve('actions::enroll-action.grouped') ?? ($this->isEnrolled() ? 'heroicon-o-user-minus' : 'heroicon-o-user-plus'));

    $this->modalIcon(fn(): string => $this->isEnrolled() ? 'heroicon-o-user-minus' : 'heroicon-o-user-plus');

    $this->keyBindings(['mod+e']);

    $this->requiresConfirmation();

    $this->authorize(function (): bool {
      $record = $this->getRecord();
      return Auth::user()?->can('enroll', $record);
    });

    $this->hidden(function (): bool {
      $record = $this->getRecord();
      return !Auth::user()?->can('enroll', $record);
    });

    $this->action(function (): void {
      $record = $this->getRecord();
      $user = Auth::user();

      if (!$user) {
        $this->failure();
        return;
      }

      $result = $this->process(function () use ($record, $user) {
        return $this->isEnrolled()
          ? $record->unenroll($user)
          : $record->enroll($user);
      });

      if (!$result) {
        $this->failure();
        return;
      }

      // Check and perform daily tasks for the user
      $record->checkDailyTasks($user);

      // Optional: Dispatch an event for course enrollment
      event(new CourseEnrollment($user, $record, !$this->isEnrolled()));

      // Dispatch events to reload the courses table and the single course view
      $this->dispatch('courses-table-reload');
      $this->dispatch('course-single-reload', [$record]);
      $this->record($record->fresh());

      $this->success();
    });
  }

  protected function isEnrolled(): bool
  {
    $record = $this->getRecord();
    $user = Auth::user();

    return method_exists($record, 'isEnrolledBy') && $record->isEnrolledBy($user);
  }
}
