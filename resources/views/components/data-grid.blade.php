{{-- resources/views/components/data-grid.blade.php --}}
@props(['data' => []]) {{-- Expecting an array or collection --}}

<div class="flex flex-wrap gap-4">
  @foreach ($data as $title => $value)
    <div class="p-4 bg-gray-100 dark:bg-gray-600 rounded shadow w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
      {{-- Title --}}
      <div class="font-bold text-gray-800 dark:text-gray-200 mb-2">
        {{ is_string($title) ? __($title) : $title }}
      </div>
      {{-- Value --}}
      <div class="text-gray-700 dark:text-gray-300">
        @empty($value)
          <span class="italic text-gray-600 dark:text-gray-400 text-sm">
            {{ __('messages.empty') }}
          </span>
        @else
          {{ is_array($value) ? json_encode($value) : $value }}
        @endempty
      </div>
    </div>
  @endforeach
</div>
