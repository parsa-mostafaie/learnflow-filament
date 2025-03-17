@props(['color', 'content'])

<span @if (!is_null($color)) class="{{ $color }} text-white px-3 py-1 rounded-full" @endif>
  {{ $content ?? $slot }}
</span>
