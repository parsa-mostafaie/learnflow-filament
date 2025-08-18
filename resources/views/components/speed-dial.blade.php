<div x-data="{ open: false }" class="fixed right-4 bottom-4 space-y-4 flex flex-col items-center"> {{-- Dropdown Container --}}
  <div x-show="open" x-transition:enter="transform transition ease-out duration-150"
    x-transition:enter-start="opacity-0 scale-75" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transform transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-75" class="flex flex-col space-y-4" x-cloak> {{-- Home Button --}}
    <a href="{{ route('welcome') }}"
      class="flex dark:hover:text-gray-300 justify-center items-center w-10 h-10 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow transition duration-300 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
      <x-heroicon-s-home class="size-5 text-lg" />
    </a>

    {{-- Filament Panel Button --}}
    @auth
      {{-- @can('access filament panel') --}}
      <a href="{{ route('filament.panel.pages.dashboard') }}" title="{{ __('speed-dial.filament') }}"
        class="flex dark:hover:text-gray-300 justify-center items-center w-10 h-10 text-indigo-600 hover:text-indigo-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow transition duration-300 ease-in-out hover:bg-indigo-50 dark:hover:bg-gray-700 focus:ring-4 focus:ring-indigo-300 focus:outline-none dark:focus:ring-indigo-500">
        <x-heroicon-s-briefcase class="size-5 text-lg" />
      </a>
      {{-- @endcan --}}
    @endauth

    {{-- Filament Login/Register Buttons --}}
    @guest
      {{-- Login --}}
      <a href="{{ route('filament.panel.auth.login') }}" title="{{ __('speed-dial.login') }}"
        class="flex justify-center items-center dark:hover:text-gray-300 w-10 h-10 text-blue-600 hover:text-blue-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow transition duration-300 ease-in-out hover:bg-blue-50 dark:hover:bg-gray-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-500">
        <x-heroicon-s-arrow-right-end-on-rectangle class="size-5 text-lg" />
      </a>

      {{-- Register --}}
      <a href="{{ route('filament.panel.auth.register') }}" title="{{ __('speed-dial.register') }}"
        class="flex justify-center items-center dark:hover:text-gray-300 w-10 h-10 text-green-600 hover:text-green-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow transition duration-300 ease-in-out hover:bg-green-50 dark:hover:bg-gray-700 focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-500">
        <x-heroicon-s-user-plus class="size-5 text-lg" />
      </a>
    @endguest
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
