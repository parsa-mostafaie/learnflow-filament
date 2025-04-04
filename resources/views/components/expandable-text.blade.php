@props(['text', 'limit' => 100, 'show_slot' => true])

@php
  use Illuminate\Support\Str;
  $isTruncated = Str::length($text) > $limit;
@endphp

<div>
  @if (Str::length($text) > 0)
    <div x-data="{ showFullText: @js(!$isTruncated) }" class="max-w-full p-2 border rounded-lg shadow-md bg-white my-2 text-justify text-wrap">
      @if ($show_slot)
        {{ $slot }}
      @endif
      {{-- Display Summary Text --}}
      <p x-show="!showFullText" class="text-gray-700 flex justify-between items-start gap-4">
        {{ $isTruncated ? Str::limit($text, $limit, '...') : $text }}
        <i class="fas fa-align-left text-gray-400"></i> {{-- Icon on the right --}}
      </p>

      {{-- Display Full Text --}}
      <p x-show="showFullText" class="text-gray-700 flex justify-between items-start gap-4">
        {{ $text }}
        <i class="fas fa-file-alt text-gray-400"></i> {{-- Icon on the right --}}
      </p>

      @if ($isTruncated)
        {{-- Toggle Button (Display only if text is truncated) --}}
        <button @click="showFullText = !showFullText"
          class="mt-2 text-sm font-medium text-blue-500 hover:text-blue-700 focus:outline-none focus:ring focus:ring-blue-300 flex justify-end items-center">
          <i class="fas fa-chevron-down" x-show="!showFullText"></i> {{-- Icon for 'Show More' --}}
          <i class="fas fa-chevron-up" x-show="showFullText"></i> {{-- Icon for 'Show Less' --}}
        </button>
      @endif
    </div>
  @endif
</div>
