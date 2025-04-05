@props(['question'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use Masmerise\Toaster\Toaster;
use App\Models\Course;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

// Define the state for the component
state(['question']);

/**
 * Function to handle the delete action
 */
$ask = function () {
    // Authorize the user to delete the question
    $this->authorize('delete', $this->question);

    LivewireAlert::title(__('Delete Question'))->confirmButtonColor('#dc3741')->denyButtonColor('#7066e0')->text(__('Are you sure you want to delete this question?'))->asConfirm()->withConfirmButton(__('Yes, delete it!'))->withDenyButton(__('No, keep it!'))->onConfirm('action')->show();
};

$action = function () {
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
    <x-danger-button wire:click="ask" title="{{ __('Force Delete') }}">
      <i class="fas fa-skull-crossbones"></i>
    </x-danger-button>
  @endcan
</div>
