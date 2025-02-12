@php
  use App\Models\Course;
  use function Livewire\Volt\{on};

  // Fetch the course with the given slug, including trashed courses
  $course = Course::withTrashed()->where('slug', $id)->firstOrFail();
@endphp

<x-app-layout>
  {{-- Header slot --}}
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ $course->title }}
    </h2>
  </x-slot>

  {{-- Main content section --}}
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Course card container --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          {{-- Livewire component for the course card --}}
          <livewire:courses.card :$course />
        </div>
      </div>
      {{-- Livewire component for course learning section --}}
      <livewire:courses.learning :$course />
    </div>
  </div>
</x-app-layout>
