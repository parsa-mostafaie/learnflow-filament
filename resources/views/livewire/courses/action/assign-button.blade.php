@props(['course'])

<?php
use function Livewire\Volt\state;
use App\Models\Course;

state(['course']);

$action = function () {
    $this->dispatch('assign-to-course', course_id: $this->course->id);
};
?>

<x-primary-button wire:click="action">Assign</x-primary-button>