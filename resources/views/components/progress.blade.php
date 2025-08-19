@props(['percentage'])
@use(\Illuminate\Support\Number)

<div class="relative w-full h-5 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden shadow-md">
  {{-- Progress Bar --}}
  <div
    class="h-full rounded-full bg-gradient-to-r from-sky-400 via-indigo-500 to-fuchsia-500 
           transition-[width] duration-700 ease-in-out shadow-lg"
    style="width: {{ $percentage }}%">
  </div>

  {{-- Floating Label --}}
  <span class="absolute inset-0 flex items-center justify-center text-[0.75rem] font-bold
           text-white"
    style="text-shadow: 0 0 3px rgba(0,0,0,0.6);">
    {{ Number::percentage($percentage, 0, 2) }}
  </span>
</div>
