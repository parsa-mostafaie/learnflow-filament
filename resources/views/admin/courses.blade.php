<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-bold text-lg mb-3 *">{{__("Courses")}}</h3>
                    <livewire:courses-table lazy />
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-2">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:courses.create />
                </div>
            </div>
        </div>
    </div>

    <livewire:courses.edit />
    <livewire:courses.assign />
</x-app-layout>