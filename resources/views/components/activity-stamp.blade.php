@props(['title', 'title_link', 'slug', 'slug_link'])
<div class="flex flex-col">
  @if (!empty($title))
    <b class="text-purple-700 dark:text-purple-300"
      @if (!empty($title_link)) wire:navigate href="{{ $title_link }}" :class="['cursor-pointer']" @endif>{{ $title }}</b>
  @endif

  @if (!empty($slug))
    <i @if (!empty($slug_link)) wire:navigate href="{{ $slug_link }}" @endif>{{ $slug }}</i>
  @endif
</div>
