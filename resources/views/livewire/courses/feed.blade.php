<?php

use App\Models\Course;
use function Livewire\Volt\{mount, state, on};

state([
    'limit' => 10,
    'offset' => 0,
    'courses' => collect([]),
    'more_found' => true,
    'search' => [
        'text' => null,
        'filters' => [
            'only_enrolled' => false,
        ],
        'sortBy' => 'courses.created_at',
        'sortDirection' => 'desc',
    ],
]);

on(['course-reload' => 'searchChange']);

$loadMore = function () {
    $newCourses = Course::withTrashed()
        ->with(['user'])
        ->withCount('enrolls')
        ->withCount('questions_approved')
        ->search($this->search)
        ->skip($this->offset)
        ->take($this->limit)
        ->get();

    $this->more_found = $newCourses->isNotEmpty();
    $this->courses = $this->courses->merge($newCourses);
    $this->offset += $this->limit;
};

$searchChange = function () {
    $this->more_found = true;
    $this->offset = 0;
    $this->courses = collect([]);
    $this->loadMore();
};

mount(fn() => $this->loadMore());
?>

<div id="search-courses-section" x-data="{ loading: false }"
  @scroll.window="if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight && !loading && $wire.more_found) { loading = true; $wire.loadMore().then(() => { loading = false; }); }">
  <!-- Page Header: Title and Search Input -->
  <div class="bg-white dark:bg-gray-800 p-6 space-y-6 shadow rounded">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
      {{ __('Search Courses') }}
    </h2>
    <p class="text-sm text-gray-600 dark:text-gray-400">
      {{ __('Filter and sort courses to find your desired ones.') }}
    </p>
    <x-search-input onInput="searchChange()" model="search.text"
      placeholder="{{ __('Filter Title, description, slug of course...') }}" />
  </div>

  <!-- Filters and Sorting -->
  <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 shadow mt-2">
    <div class="space-y-4">
      <p class="font-bold text-gray-800 dark:text-gray-200">{{ __('Filters') }}</p>
      <div class="flex flex-wrap gap-4 items-center">
        <x-checkbox-filter id="enrolled-filter" model="search.filters.only_enrolled"
          label="{{ __('Only Enrolled Courses') }}" wire:change="searchChange" />
      </div>
    </div>
    <div class="space-y-4 mt-6">
      <p class="font-bold text-gray-800 dark:text-gray-200">{{ __('Sort By') }}</p>
      <div class="flex gap-4 flex-wrap items-center">
        <x-dropdown-filter id="sort-column" model="search.sortBy" label="{{ __('Column') }}" :options="[
            'courses.created_at' => __('Creation Date'),
            'enrolls_count' => __('Enrolls Count'),
            'questions_approved_count' => __('Questions Count'),
            'title' => __('Title'),
        ]"
          wire:change="searchChange" />
        <x-dropdown-filter id="sort-direction" model="search.sortDirection" label="{{ __('Direction') }}"
          :options="[
              'asc' => __('Ascending'),
              'desc' => __('Descending'),
          ]" wire:change="searchChange" />
      </div>
    </div>
  </div>

  <!-- Courses List -->
  <div class="p-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg flex flex-col gap-2">
      @if ($this->courses->isNotEmpty())
        @foreach ($this->courses as $course)
          <div class="p-2 border border-1 rounded-lg dark:border-0" wire:key="container-course-{{ $course->id }}">
            <livewire:courses.card :$course :in_feed='true' wire:key="{{ $course->id }}" />
          </div>
        @endforeach
      @else
        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
          {{ __('No results found') }}
        </div>
      @endif
    </div>
  </div>

  {{-- Loading indicator for fetching more courses --}}
  <div wire:loading class="text-purple-900 dark:text-purple-400 px-2">
    {{ __('Loading more courses') }}...
  </div>
</div>
