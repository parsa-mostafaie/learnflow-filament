@props(['course'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use App\Models\Course;

state(['course']);

$action = function () {
    $this->authorize('forceDelete', $this->course);

    $this->course->forceDelete();

    $this->dispatch('courses-table-reload');
    $this->dispatch('course-single-reload', $this->course->id);
};
?>

<div>
    @can('delete', $this->course)
        <x-danger-button wire:click="action">Force Delete</x-danger-button>
    @endcan
</div>