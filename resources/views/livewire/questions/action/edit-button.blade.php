@props(['question'])

<?php
use function Livewire\Volt\state;
use App\Models\Course;

state(['question']);

$action = function () {
    $this->dispatch('edit-question', question_id: $this->question->id);
};
?>

<x-primary-button wire:click="action">Edit</x-primary-button>