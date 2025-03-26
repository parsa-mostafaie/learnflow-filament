@props(['course'])

<?php
use function Livewire\Volt\state;
use App\Models\Course;

// Define the state for the component
state(['course']);

// Define the action to be performed when the button is clicked
$action = function () {
    $this->dispatch('edit-course', course_id: $this->course->id);
};
?>

{{-- Primary button component with click event handler --}}
<div>
  @can('update', $this->course)
    <x-primary-button wire:click="action" title="{{ __('Edit') }}">
      <i class="fas fa-edit"></i>
    </x-primary-button>
  @endcan
</div>
