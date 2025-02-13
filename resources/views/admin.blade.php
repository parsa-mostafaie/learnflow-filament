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
                    {{__("Try Managing one of options below")}}:
                    <div class="flex gap-1 mt-2 dark:bg-gray-900 p-2 rounded dark:text-white">
                        <div class="p-3 rounded bg-purple-500 text-white dark:bg-purple-800 select-none flex flex-col scale-on-hover">
                            <div class="border-b-2 border-gray-100 dark:border-gray-500">{{ __("Questions") }}</div>
                            <p wire:navigate href="{{ route('admin.questions') }}" class="text-center mt-2">
                                <i
                                    class="scale-on-hover fas fa-arrow-right border rounded-full p-1 text-xs text-gray-300 border-gray-100 hover:bg-gray-100 hover:text-gray-500 dark:border-gray-500 dark:hover:bg-gray-600 dark:hover:text-white"></i>
                            </p>
                        </div>
                        <div class="p-3 rounded bg-purple-500 text-white dark:bg-purple-800 select-none flex flex-col scale-on-hover">
                            <div class="border-b-2 border-gray-100 dark:border-gray-500">{{ __("Courses") }}</div>
                            <p wire:navigate href="{{ route('admin.courses') }}" class="text-center mt-2">
                                <i
                                    class="scale-on-hover fas fa-arrow-right border rounded-full p-1 text-xs text-gray-300 border-gray-100 hover:bg-gray-100 hover:text-gray-500 dark:border-gray-500 dark:hover:bg-gray-600 dark:hover:text-white"></i>
                            </p>
                        </div>
                        @can('viewAny',"App\\Models\\User")
                        <div class="p-3 rounded bg-purple-500 text-white dark:bg-purple-800 select-none flex flex-col scale-on-hover">
                            <div class="border-b-2 border-gray-100 dark:border-gray-500">{{ __("Users") }}</div>
                            <p wire:navigate href="{{ route('admin.users') }}" class="text-center mt-2">
                                <i
                                    class="scale-on-hover fas fa-arrow-right border rounded-full p-1 text-xs text-gray-300 border-gray-100 hover:bg-gray-100 hover:text-gray-500 dark:border-gray-500 dark:hover:bg-gray-600 dark:hover:text-white"></i>
                            </p>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>