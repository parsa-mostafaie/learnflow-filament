@props(['course'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use App\Models\Course;
use Masmerise\Toaster\Toaster;

// Define the state for the component
state(['course']);

// Define the action to be performed when the button is clicked
$action = function () {
    // Check if the user is authorized to restore the course
    $this->authorize('restore', $this->course);

    // Restore the course
    $this->course->restore();

    // Dispatch events to reload the courses table and the single course view
    $this->dispatch('courses-table-reload');
    $this->dispatch('course-single-reload', $this->course->id);
    Toaster::info(__('Restored.'));
};
?>

{{-- Restore button with authorization check --}}
<div>
  @can('restore', $this->course)
    <x-primary-button wire:click="action" title="{{ __('actions.restore') }}">
      <x-heroicon-s-arrow-uturn-left class="w-4 h-4" />
    </x-primary-button>
  @endcan
</div>
