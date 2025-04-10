<div x-data="{ open: false }" class="fixed right-4 bottom-4 space-y-4 flex flex-col items-center"> {{-- Dropdown Container --}}
  <div x-show="open" x-transition:enter="transform transition ease-out duration-150"
    x-transition:enter-start="opacity-0 scale-75" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transform transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-75" class="flex flex-col space-y-4" x-cloak> {{-- Home Button --}}
    <a href="{{ route('welcome') }}" wire:navigate
      class="flex justify-center items-center w-10 h-10 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow transition duration-300 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
      <i class="fas fa-home text-lg"></i>
    </a>
    {{-- Courses Button --}}
    @can('manage any courses')
      <a href="{{ route('admin.courses') }}" wire:navigate title="{{ __('Courses') }}"
        class="flex justify-center items-center w-10 h-10 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow transition duration-300 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
        <i class="fas fa-book text-lg"></i>
      </a>
    @endcan {{-- Questions Button --}}
    @can('manage any questions')
      <a href="{{ route('admin.questions') }}" wire:navigate title="{{ __('Questions') }}"
        class="flex justify-center items-center w-10 h-10 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow transition duration-300 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
        <i class="fas fa-question-circle text-lg"></i>
      </a>
    @endcan {{-- Users Button --}}
    @can('manage users or activities')
      <a href="{{ route('admin.users') }}" wire:navigate title="{{ __('Users') }}"
        class="flex justify-center items-center w-10 h-10 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow transition duration-300 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
        <i class="fas fa-users text-lg"></i>
      </a>
    @endcan
  </div>

  {{-- Toggle Button --}}
  <button x-on:click="open = !open" :aria-expanded="open.toString()"
    class="flex items-center justify-center text-white bg-blue-700 rounded-full w-12 h-12 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800 transition duration-300 ease-in-out">
    <svg :class="{ 'rotate-45': open }" class="w-4 h-4 transition-transform duration-150"
      xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18" aria-hidden="true">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
    </svg>
  </button>
</div>
