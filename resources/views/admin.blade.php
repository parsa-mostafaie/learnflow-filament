<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Admin') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          {{ __('Try Managing one of options below') }}:
          <div
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 lg:grid-cols-10 gap-4 mt-2 dark:bg-gray-900 p-2 rounded dark:text-white">
            @can('manage any questions')
              <x-manage-card :name="__('Questions ?')" :href="route('admin.questions')" />
            @endcan
            @can('manage any courses')
            <x-manage-card :name="__('Courses')" :href="route('admin.courses')" />
            @endcan
            @can('manage users or activities')
              <x-manage-card :name="__('Users')" :href="route('admin.users')" />
            @endcan
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
