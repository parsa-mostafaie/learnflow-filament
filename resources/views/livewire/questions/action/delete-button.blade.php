@props(['question'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use App\Models\Course;

state(['question']);

$action = function () {
    $this->authorize('delete', $this->question);

    $this->question->delete();

    $this->dispatch('questions-table-reload');
    $this->dispatch('question-single-reload', $this->question->id);
};
?>

<div>
    @can('delete', $this->question)
        <x-danger-button wire:click="action">Delete</x-danger-button>
    @endcan
</div>