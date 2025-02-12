@props(['course', 'in_show' => false])

<?php
use function Livewire\Volt\{computed, state, on};
use App\Models\Course;

// Define the state for the component
state(['course', 'in_show']);

// Define an event listener to reload the course model when the 'course-single-reload' event is dispatched
on([
    'course-single-reload' => function ($course) {
        if ($course == $this->course_model->id) {
            $this->course_model->fresh();
        }
    },
]);

// Define a computed property to get the course model
$course_model = computed(fn() => $this->course instanceof App\Models\Course ? $this->course : Course::withTrashed()->findOrFail($this->course));
?>

{{-- Container for action buttons --}}
<div>
  <x-button-group>
    {{-- Action buttons for trashed courses --}}
    @if ($this->course_model->trashed())
      @can('forceDelete', $this->course_model)
        <livewire:courses.action.forcedelete-button :course="$this->course_model" />
      @endcan
      @can('restore', $this->course_model)
        <livewire:courses.action.restore-button :course="$this->course_model" />
      @endcan
    @else
      @can('delete', $this->course_model)
        <livewire:courses.action.delete-button :course="$this->course_model" />
      @endcan
    @endif

    {{-- Action buttons for non-trashed courses when not in show mode --}}
    @if (!$in_show)
      @can('update', $this->course_model)
        <livewire:courses.action.edit-button :course="$this->course_model" />
      @endcan
      @can('assign', $this->course_model)
        <livewire:courses.action.assign-button :course="$this->course_model" />
      @endcan
    @endif

    {{-- Enroll/Unenroll button --}}
    @can('enroll', $this->course_model)
      <livewire:courses.action.enroll-button :course="$this->course_model" />
    @endcan

    {{-- Show button when not in show mode --}}
    @if (!$in_show)
      <livewire:courses.action.show-button :course="$this->course_model" />
    @endif
  </x-button-group>
</div>
