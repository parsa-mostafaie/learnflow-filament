@props(['course'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use App\Models\Course;

state(['course']);

$action = function () {
    $this->authorize('enroll', $this->course);

    if ($this->course->is_enrolled) {
        $this->course->unenroll($user = Auth::user());
        // event(new CourseUnenrollment($user, $this->course));
    } else {
        $this->course->enroll($user = Auth::user());
        $this->course->checkDailyTasks($user);
        // event(new CourseEnrollment($user, $this->course));
    }

    $this->dispatch('courses-table-reload');
    $this->dispatch('course-single-reload', $this->course->id);
};
?>

<div>
    @can('enroll', $this->course)
        <x-primary-button wire:click="action">{{ $this->course->is_enrolled ? 'Une' : 'E' }}nroll</x-primary-button>
    @endcan
</div>