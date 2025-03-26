@props(['model', 'placeholder' => __('Search...'), 'onInput'])

<div class="relative">
  <x-text-input type="text" wire:model.live="{{ $model }}" placeholder="{{ $placeholder }}"
    wire:input="{{ $onInput }}" class="w-full" />
</div>
