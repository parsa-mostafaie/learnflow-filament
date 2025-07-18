@props(['text', 'limit' => 100, 'show_slot' => true])

@php
  use Illuminate\Support\Str;

  // Sanitize the rich text HTML (Filament rich text content)
  $safeHtml = str($text)->markdown()->sanitizeHtml();

  // Remove HTML tags to calculate plain text length
  $strippedText = strip_tags($safeHtml);

  // Check if text needs truncation
  $isTruncated = Str::length($strippedText) > $limit;

  // Truncate plain text only for preview
  $truncatedText = Str::limit($strippedText, $limit, '...');
@endphp

<div>
  @if (Str::length($strippedText) > 0)
    <div x-data="{ showFullText: @js(!$isTruncated) }" class="max-w-full p-2 border rounded-lg shadow-md bg-white my-2 text-justify text-wrap">

      {{-- Optional slot content --}}
      @if ($show_slot)
        {{ $slot }}
      @endif

      {{-- Truncated view (plain text styled like HTML) --}}
      <div x-show="!showFullText" class="text-gray-700 flex justify-between items-start gap-4">
        <p class="flex-1 max-w-full">
          {!! $truncatedText !!}
        </p>
        <x-heroicon-s-document-text class="w-5 h-5 text-gray-400" />
      </div>

      {{-- Full rich text content (sanitized HTML) --}}
      <div x-show="showFullText" class="text-gray-700 flex justify-between items-start gap-4">
        <div class="flex-1 max-w-full prose prose-sm">
          {!! $safeHtml !!}
        </div>
        <x-heroicon-s-clipboard-document class="w-5 h-5 text-gray-400" />
      </div>

      {{-- Toggle button (show more / less) --}}
      @if ($isTruncated)
        {{-- Toggle Button (Display only if text is truncated) --}}
        <button @click="showFullText = !showFullText"
          class="mt-2 text-sm font-medium text-blue-500 hover:text-blue-700 focus:outline-none focus:ring focus:ring-blue-300 flex justify-end items-center">

          <x-heroicon-s-chevron-down x-show="!showFullText" class="w-5 h-5" />
          <x-heroicon-s-chevron-up x-show="showFullText" class="w-5 h-5" />
        </button>
      @endif
    </div>
  @endif
</div>
