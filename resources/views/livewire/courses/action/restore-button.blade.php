@props(['course'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use App\Models\Course;

state(['course']);

$action = function () {
    $this->authorize('forceDelete', $this->course);

    $this->course->restore();

    $this->dispatch('courses-table-reload');
    $this->dispatch('course-single-reload', $this->course->id);
};
?>

<div>
    @can('restore', $this->course)
        <x-primary-button wire:click="action">Restore</x-primary-button>
    @endcan
</div>