@props(['course'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment};
use App\Models\Course;
use Masmerise\Toaster\Toaster;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

// Define the state for the component
state(['course']);

$ask = function () {
    $this->authorize('enroll', $this->course);

    LivewireAlert::title(__('Unenroll from Course'))->confirmButtonColor('#dc3741')->denyButtonColor('#7066e0')->text(__('Are you sure you want to unenroll from this course?'))->asConfirm()->withConfirmButton(__('Yes, unenroll me!'))->withDenyButton(__('No, keep me enrolled!'))->onConfirm('unenroll')->show();
};

$unenroll = function () {
    // Check if the user is authorized to enroll in the course
    $this->authorize('enroll', $this->course);

    // Check if the course is already enrolled
    if ($this->course->is_enrolled) {
        // Unenroll the user from the course
        $this->course->unenroll($user = Auth::user());
        // Optional: Dispatch an event for course unenrollment
        event(new CourseEnrollment($user, $this->course, true));

        // Dispatch events to reload the courses table and the single course view
        $this->dispatch('courses-table-reload');
        $this->dispatch('course-single-reload', $this->course->id);
        Toaster::info(__('messages.done'));
    }
};

// Define the action to be performed when the button is clicked
$action = function () {
    // Check if the user is authorized to enroll in the course
    $this->authorize('enroll', $this->course);

    // Check if the course is already enrolled
    if ($this->course->is_enrolled) {
        $this->ask();
        return;
    } else {
        // Enroll the user in the course
        $this->course->enroll($user = Auth::user());
        // Check and perform daily tasks for the user
        $this->course->checkDailyTasks($user);
        // Optional: Dispatch an event for course enrollment
        event(new CourseEnrollment($user, $this->course));
    }

    // Dispatch events to reload the courses table and the single course view
    $this->dispatch('courses-table-reload');
    $this->dispatch('course-single-reload', $this->course->id);
    Toaster::info(__('messages.done'));
};
?>

{{-- Enroll button with authorization check --}}
<div>
  @can('enroll', $this->course)
    <x-primary-button wire:click="action"
      title="{{ $this->course->is_enrolled ? __('actions.unenroll') : __('actions.enroll') }}">
      @if ($this->course->is_enrolled)
        <x-heroicon-s-user-minus class="w-4 h-4" /> <!-- Icon for Unenroll -->
      @else
        <x-heroicon-s-user-plus class="w-4 h-4" /> <!-- Icon for Enroll -->
      @endif
    </x-primary-button>
  @endcan
</div>
