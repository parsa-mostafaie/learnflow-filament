@props(['name', 'href'])
<div
  class="p-3 rounded bg-purple-500 text-white dark:bg-purple-800 select-none flex flex-col scale-on-hover lg:w-1/10 sm:w-full"
  style="width: 100%;">
  <div class="border-b-2 border-gray-100 dark:border-gray-500 text-center">{{ $name }}</div>
  <p wire:navigate href="{{ $href }}" class="text-center mt-2">
    <i
      class="scale-on-hover fas fa-arrow-left rounded-full p-2 w-6 h-6 text-xs text-purple-200 hover:bg-purple-100 hover:text-purple-400 dark:hover:bg-purple-700 dark:hover:text-purple-300 flex items-center justify-center mx-auto"></i>
  </p>
</div>
