<x-app-layout>
  {{-- Header slot --}}
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Admin') }}
    </h2>
  </x-slot>

  {{-- Main content section --}}
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Courses table container --}}
      <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          {{-- Courses title --}}
          <h3 class="font-bold text-lg mb-3">{{ __('Courses') }}</h3>
          {{-- Livewire component for the courses table --}}
          <livewire:courses-table lazy />
        </div>
      </div>
      {{-- Course creation container --}}
      <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-2">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          {{-- Livewire component for creating courses --}}
          <livewire:courses.create />
        </div>
      </div>
    </div>
  </div>

  {{-- Livewire components for editing and assigning courses --}}
  <livewire:courses.edit />
  <livewire:courses.assign />
</x-app-layout>
