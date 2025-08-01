@props(['in_feed'])

<?php
use function Livewire\Volt\{state, computed, on, mount, uses};
use App\Models\Course, App\Events\CourseViewedEvent;
use Illuminate\Support\Facades\Gate;

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
    Gate::authorize('view', $this->course);

    if ($this->in_feed) {
        return;
    }

    // Dispatch an event when the course is viewed
    // event(new CourseViewedEvent($this->course, auth()->user()));
});
?>

@use(\Illuminate\Support\Number)
<div>
  <div class="flex gap-4 md:gap-6 sm:flex-row flex-col flex-wrap items-center md:items-start">
    <div class="w-full md:w-auto flex justify-center">
      {{-- Course image --}}
      <img src="{{ $course->image_url }}" loading="lazy" class="sm:max-w-[300px] sm:max-h-[200px] rounded-lg" alt="Course Image" />
    </div>
    <div class="grow text-center sm:text-start">
      <h1 class="font-bold text-lg flex justify-between sm:flex-row flex-col mb-2 gap-2 items-center">
        <a wire:navigate href="{{ route('course.single', $course->slug) }}">
          <x-heroicon-o-book-open class="me-2 w-5 h-5 inline" /> {{ $course->title }}
        </a>
        <livewire:courses.actions :course="$this->course" :in_show="true" />
      </h1>
      <p class="text-gray-400">
        <x-heroicon-s-user class="me-2 w-5 h-5 inline" /> {{ $course->author->name }}
      </p>
      <p class="text-gray-500">
        <x-heroicon-s-users class="me-2 w-5 h-5 inline" /> {{ $course->formatted_enrolls_count }} {{ __('users.plural') }}
      </p>
      <p class="text-gray-500">
        <x-heroicon-s-question-mark-circle class="me-2 w-5 h-5 inline" />{{ $course->formatted_approved_questions_count }} {{ __('questions.plural') }}
      </p>
    </div>
  </div>

  <x-expandable-text :text="$course->description">
    <span class="font-bold text-gray-700">
      <x-heroicon-o-information-circle class="me-2 w-5 h-5 inline" /> {{ __('courses.columns.description') }}
    </span>
  </x-expandable-text>

</div>
