@props(['id', 'model', 'label'])

<div class="flex items-center gap-2">
  <input id="{{ $id }}" type="checkbox" wire:model.live="{{ $model }}" wire:change="searchChange"
    class="rounded-lg border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-600 text-gray-800 dark:text-gray-200">
  <label for="{{ $id }}" class="text-sm text-gray-600 dark:text-gray-400 me-2">
    {{ $label }}
  </label>
</div>
