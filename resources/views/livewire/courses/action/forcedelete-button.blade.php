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
    // Check if the user is authorized to force delete the course
    $this->authorize('forceDelete', $this->course);

    // Force delete the course
    $this->course->forceDelete();

    // Dispatch events to reload the courses table and the single course view
    $this->dispatch('courses-table-reload');
    $this->dispatch('course-single-reload', $this->course->id);
    Toaster::warning(__('Deleted.'));
};
?>

{{-- Force Delete button with authorization check --}}
<div>
  @can('delete', $this->course)
    <x-danger-button wire:click="action" title="{{ __('Force Delete') }}">
      <i class="fas fa-skull-crossbones"></i>
    </x-danger-button>
  @endcan
</div>
