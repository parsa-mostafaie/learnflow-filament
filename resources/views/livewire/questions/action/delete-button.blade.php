@props(['question'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use Masmerise\Toaster\Toaster;
use App\Models\Course;

// Define the state for the component
state(['question']);

/**
 * Function to handle the delete action
 */
$action = function () {
    // Authorize the user to delete the question
    $this->authorize('delete', $this->question);

    // Delete the question
    $this->question->delete();

    // Dispatch events to reload the questions table and the single question view
    $this->dispatch('questions-table-reload');
    $this->dispatch('question-single-reload', $this->question->id);
    Toaster::warning(__('Deleted.'));
};
?>

{{-- Container for the delete button --}}
<div>
  @can('delete', $this->question)
    {{-- Delete button with authorization check --}}
    <x-danger-button wire:click="action">{{ __('Force Delete') }}</x-danger-button>
  @endcan
</div>
