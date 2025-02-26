@props(['color', 'content'])

<span class="{{ $color }} text-white px-3 py-1 rounded-full">
  {{ $content ?? $slot }}
</span>
