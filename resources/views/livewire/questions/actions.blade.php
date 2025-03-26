@props(['question'])

<?php
use function Livewire\Volt\{computed, state, on};
use App\Models\Question;

// Define the state for the component
state(['question', 'in_show']);

// Define a computed property to get the question model
$question_model = computed(fn() => $this->question instanceof App\Models\Question ? $this->question : Question::findOrFail($this->question));
?>

{{-- Container for the action buttons --}}
<div>
  <x-button-group>
    @can('delete', $this->question_model)
      {{-- Render the delete button if the user has permission --}}
      <livewire:questions.action.delete-button :question="$this->question_model" />
    @endcan
    @can('update', $this->question_model)
      {{-- Render the edit button if the user has permission --}}
      <livewire:questions.action.edit-button :question="$this->question_model" />
    @endcan
    @can('changeStatus', $this->question_model)
      <livewire:questions.action.status-button :question="$this->question_model" />
    @endcan
  </x-button-group>
</div>
