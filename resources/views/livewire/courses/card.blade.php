@props(['in_feed'])

<?php
use function Livewire\Volt\{state, computed, on, mount};
use App\Models\Course, App\Events\CourseViewedEvent;

// Define the state for the component
state(['course', 'in_feed']);

// Define an event listener to reload the course model when the 'course-single-reload' event is dispatched
on([
    'course-single-reload' => function ($course) {
        if ($course == $this->course->id) {
            $this->course->fresh();
        }
    },
]);

// Define a mount function to handle actions when the component is initialized
mount(function () {
    if ($this->in_feed) {
        return;
    }
    // Dispatch an event when the course is viewed
    // event(new CourseViewedEvent($this->course, auth()->user()));
});
?>

<div class="flex gap-6 sm:flex-row flex-col">
  <img src="{{ $course->image_url }}" class="max-h-[200px] rounded-lg" alt="Course Image" />
  <div class="grow">
    <h1 class="font-bold text-lg flex justify-between sm:flex-row flex-col mb-2 gap-2 items-center">
      <a wire:navigate href="{{ route('course.single', $course->slug) }}">{{ $course->title }}</a>
      <livewire:courses.actions :course="$this->course" :in_show="true" />
    </h1>
    {{ $course->description }}
    <p class="text-gray-400">{{ $course->author->name }}</p>
    <p class="text-gray-500">{{ $course->enrolls()->count() }} {{ __('Enrolled Users') }}</p>
    <p class="text-gray-500">{{ $course->questions()->count() }} {{ __('Questions') }}</p>
  </div>
</div>
