<?php

use App\Models\Course;
use function Livewire\Volt\{mount, state};

state(['limit' => 5, 'offset' => 0, 'search' => '', 'courses' => collect([]), 'more_found' => true]);

$loadMore = function () {
    // Fetch new courses based on the current offset and limit
    $newCourses = Course::withTrashed()
        ->whereLike('title', "%{$this->search}%")
        ->skip($this->offset)
        ->take($this->limit)
        ->get()
        ->shuffle();

    // Update the count of new courses
    $this->more_found = $newCourses->count() != 0;

    // Merge new courses into the existing collection
    $this->courses = $this->courses->merge($newCourses);

    // Increment the offset for the next load
    $this->offset += $this->limit;
};

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
      <x-text-input wire:input="searchChange()"  wire:model.throttle="search" />
    </div>
    @foreach ($this->courses as $course)
      <livewire:courses.card :$course :in_feed='true' wire:key="{{ $course->id }}" />
    @endforeach
    @if ($this->courses->count() == 0)
      <div class="text-purple-900 dark:text-purple-400 px-2 pt-2">
        {{ __('No results found') }}!</div>
    @endif
  </div>

  <div wire:loading wire:target="loadMore" class="text-purple-900 dark:text-purple-400 px-2 pt-2">
    {{ __('Loading more courses') }}...
  </div>
</div>
