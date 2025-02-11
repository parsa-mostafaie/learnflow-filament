@props(['course'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use App\Models\Course;

state(['course']);

$action = function () {
    $this->authorize('delete', $this->course);

    $this->course->delete();

    $this->dispatch('courses-table-reload');
    $this->dispatch('course-single-reload', $this->course->id);
};
?>

<div>
    @can('delete', $this->course)
        <x-danger-button wire:click="action">Delete</x-danger-button>
    @endcan
</div>