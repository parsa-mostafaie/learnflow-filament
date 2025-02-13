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
<x-primary-button wire:click="action">{{ __('Edit') }}</x-primary-button>
