@props(['course'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment};
use App\Models\Course;
use Masmerise\Toaster\Toaster;

// Define the state for the component
state(['course']);

// Define the action to be performed when the button is clicked
$action = function () {
    // Check if the user is authorized to enroll in the course
    $this->authorize('enroll', $this->course);

    // Check if the course is already enrolled
    if ($this->course->is_enrolled) {
        // Unenroll the user from the course
        $this->course->unenroll($user = Auth::user());
        // Optional: Dispatch an event for course unenrollment
        event(new CourseEnrollment($user, $this->course, true));
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
    Toaster::info(__('Done.'));
};
?>

{{-- Enroll button with authorization check --}}
<div>
  @can('enroll', $this->course)
    <x-primary-button wire:click="action" 
      title="{{ $this->course->is_enrolled ? __('Unenroll') : __('Enroll') }}">
      @if($this->course->is_enrolled)
        <i class="fas fa-user-minus"></i> <!-- Icon for Unenroll -->
      @else
        <i class="fas fa-user-plus"></i> <!-- Icon for Enroll -->
      @endif
    </x-primary-button>
  @endcan
</div>
