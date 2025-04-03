@props(['color', 'content'])

<div>
  @if (is_array($content))
    @foreach ($content as $item)
      <x-pill :color="$color" :content="$item" />
    @endforeach
  @else
    <span @if (!is_null($color)) class="{{ $color }} text-white px-3 py-1 rounded-full" @endif>
      {{ $content ?? $slot }}
    </span>
  @endif
</div>
