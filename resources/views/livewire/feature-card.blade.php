@props(['modal', 'title', 'text'])

<?php
use function Livewire\Volt\state;

state(['title', 'modal','text'])
?>

<div class="bg-white p-6 rounded shadow scale-on-hover flex flex-col dark:bg-gray-800">
  <h3 class="text-xl font-bold mb-2">{{ $title }}</h3>
  <p class="mb-4 text-justify">
    {{ $text }}
  </p>
  <div class="mt-auto text-center">
    <a class="text-purple-700 hover:underline dark:text-purple-500 select-none cursor-pointer"
      wire:click="dispatch('open-modal', '{{ $modal }}')">
      {{ __('messages.learn-more') }}
    </a>
  </div>
</div>
