<?php

use App\Models\Course;
use function Livewire\Volt\{mount, state, on};

// Define the state for the component
state(['limit' => 5, 'offset' => 0, 'courses' => collect([]), 'more_found' => true, 'search' => ['search' => '', 'sortBy' => 'created_at', 'filters' => []]]);

on(['course-single-reload' => 'searchChange']);

// Define the loadMore function to fetch new courses
$loadMore = function () {
    // Fetch new courses based on the current offset and limit
    $newCourses = Course::withTrashed()
        ->withCount('enrolls')
        ->withCount('questions')
        ->search($this->search)
        ->skip($this->offset)
        ->take($this->limit)
        ->get()
        ->shuffle()
        ->sortBy($this->search['sortBy'] ?: 'created_at', descending: true);

    // Update the count of new courses
    $this->more_found = $newCourses->isNotEmpty();

    // Merge new courses into the existing collection
    $this->courses = $this->courses->merge($newCourses);

    // Increment the offset for the next load
    $this->offset += $this->limit;
};

// Define the searchChange function to handle search input changes
$searchChange = function () {
    $this->more_found = true;
    $this->offset = 0;
    $this->courses = collect([]);
    $this->loadMore();
};

// Load initial courses when the component mounts
mount(fn() => $this->loadMore());
?>

<div x-data="{ loading: false }"
  @if ($this->more_found) @scroll.window="if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) { if (!loading) { loading = true; $wire.loadMore().then(() => { loading = false; }); } }" @endif>
  <div class="flex flex-col gap-6">
    <div class="flex gap-1 flex-wrap flex-col">
      {{-- Search input for filtering courses --}}
      <x-text-input wire:input.throttle="searchChange()" wire:model.throttle="search.search"
        placeholder="{{ __('Filter Title, description, slug of course...') }}" />
      <div class="bg-purple-500 dark:bg-purple-400 text-gray-100 p-3 rounded">
        <div class="flex items-center mb-4">
          <input id="only-enrolled" type="checkbox" value="" wire:model.throttle="search.filters.only_enrolled"
            wire:change.throttle="searchChange"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="only-enrolled" class="ms-2 text-sm font-medium">{{ __('Only Enrolled Courses') }}</label>
        </div>
        <hr>
        <p class="font-bold my-2">{{ __('Sort By...') }}</p>
        <div class="flex items-center sm:flex-row flex-col">
          <div>
            <input id="enrolls_first" wire:model="search.sortBy" type="radio" value="enrolls_count"
              name="enrolls-first"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              wire:change="searchChange">
            <label for="enrolls_first" class="mx-2 text-sm font-medium">{{ __('More Enrolls First') }}</label>
          </div>
          <div><input id="questions_first" wire:model="search.sortBy" type="radio" value="questions_count"
              name="enrolls-first"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              wire:change="searchChange">
            <label for="questions_first" class="mx-2 text-sm font-medium">{{ __('More questions first') }}</label>
          </div>
          <div><input id="news_first" type="radio" wire:model="search.sortBy" value="courses.created_at"
              name="news_first"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              wire:change="searchChange" checked>
            <label for="news_first" class="mx-2 text-sm font-medium">{{ __('New courses first') }}</label>
          </div>
        </div>
      </div>
    </div>
    {{-- Loop through the list of courses and display each one using the course card component --}}
    @foreach ($this->courses as $course)
      <div class="p-2 border border-1 rounded-lg">
        <livewire:courses.card :$course :in_feed='true' wire:key="{{ $course->id }}" />
      </div>
    @endforeach
    {{-- Display a message if no courses are found --}}
    @if ($this->courses->count() == 0)
      <div class="text-purple-900 dark:text-purple-400 px-2 pt-2 -mt-6">
        {{ __('No results found') }}!</div>
    @endif
  </div>

  {{-- Loading indicator for fetching more courses --}}
  <div wire:loading class="text-purple-900 dark:text-purple-400 px-2 pt-2">
    {{ __('Loading more courses') }}...
  </div>
</div>
