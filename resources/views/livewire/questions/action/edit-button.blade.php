@props(['question'])

<?php
use function Livewire\Volt\state;
use App\Models\Course;

// Define the state for the component
state(['question']);

/**
 * Function to handle the edit action
 */
$action = function () {
    // Dispatch an event to edit the question
    $this->dispatch('edit-question', question_id: $this->question->id);
};
?>

{{-- Primary button for editing the question --}}
<x-primary-button wire:click="action">Edit</x-primary-button>
