@props(['course'])

<?php
use function Livewire\Volt\state;
use App\Models\Course;

// Define the state for the component
state(['course']);

// Define the action to be performed when the button is clicked
$action = function () {
    $this->dispatch('assign-to-course', course_id: $this->course->id);
};

$action_multiple = function () {
    $this->dispatch('multiple-assign-to-course', course_id: $this->course->id);
};
?>

{{-- Primary button component with click event handler --}}
<div>
  <x-button-group>
    @can('assign', $this->course)
      <x-primary-button wire:click="action">{{ __('Assign') }}</x-primary-button>
    @endcan
    @can('assignMany', $this->course)
      <x-primary-button wire:click="action_multiple">{{ __('Assign Multiple') }}</x-primary-button>
    @endcan
  </x-button-group>
</div>
