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
      {{-- Users table container --}}
      @can('manage any users')
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            {{-- Users title --}}
            <h3 class="font-bold text-lg mb-3">{{ __('Users') }}</h3>
            {{-- Livewire component for the users table --}}
            <livewire:users-table lazy />
          </div>
        </div>
      @endcan
      @can('manage any activities')
        <div class="bg-white dark:bg-gray-800 shadow-sm mt-2 sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <h3 class="font-bold text-lg mb-3">{{ __('Logs') }}</h3>
            {{-- Livewire component for the activities table --}}
            <livewire:activities-table lazy />
          </div>
        </div>
      @endcan
    </div>
  </div>
</x-app-layout>
