@props(['course'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use App\Models\Course;
use Masmerise\Toaster\Toaster;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

// Define the state for the component
state(['course']);

$ask = function () {
    $this->authorize('delete', $this->course);

    LivewireAlert::title(__('Delete Course'))->confirmButtonColor('#dc3741')->denyButtonColor('#7066e0')->text(__('Are you sure you want to delete this course?'))->asConfirm()->withConfirmButton(__('Yes, delete it!'))->withDenyButton(__('No, keep it!'))->onConfirm('action')->show();
};

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
    <x-danger-button wire:click="ask" title="{{ __('Force Delete') }}">
      <i class="fas fa-skull-crossbones"></i>
    </x-danger-button>
  @endcan
</div>
