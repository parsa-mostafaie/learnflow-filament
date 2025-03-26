@props(['question'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Models\Question;
use Masmerise\Toaster\Toaster;

// Define the state for the component
state(['question']);

// Define the action to be performed when the button is clicked
$action = function ($newStatus) {
    // Check if the user is authorized to enroll in the question
    $this->authorize('changeStatus', $this->question);

    if ($this->question->status != $newStatus) {
        $this->question->setStatus($newStatus);
        // Optional: Dispatch an event for question st-change
        // event(new QuestionStatusChange($user, $this->question, true));
    }

    // Dispatch events to reload the questions table and the single question view
    $this->dispatch('questions-table-reload');
    Toaster::info(__('Done.'));
};

$pend = fn() => $this->action('pending');
$approve = fn() => $this->action('approved');
$reject = fn() => $this->action('rejected');
?>

{{-- Enroll button with authorization check --}}
<div>
  <x-button-group>
    @can('changeStatus', $this->question)
      @if ($this->question->status != 'pending')
        <x-primary-button wire:click="pend" title="{{ __('Move to pending') }}">
          <i class="fas fa-clock"></i>
        </x-primary-button>
      @endif
      @if ($this->question->status != 'approved')
        <x-secondary-button wire:click="approve" title="{{ __('Approve') }}">
          <i class="fas fa-check-circle"></i>
        </x-secondary-button>
      @endif
      @if ($this->question->status != 'rejected')
        <x-danger-button wire:click="reject" title="{{ __('Reject') }}">
          <i class="fas fa-times-circle"></i>
        </x-danger-button>
      @endif
    @endcan
  </x-button-group>
</div>
