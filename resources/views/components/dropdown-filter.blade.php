@props(['id', 'model', 'label', 'options'])

<div>
  <label for="{{ $id }}" class="text-sm text-gray-600 dark:text-gray-400 me-2">
    {{ $label }}:
  </label>
  <select id="{{ $id }}" wire:model.live="{{ $model }}" wire:change="searchChange"
    class="rounded-lg p-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-800 dark:text-gray-200">
    @foreach ($options as $value => $text)
      <option value="{{ $value }}">{{ $text }}</option>
    @endforeach
  </select>
</div>
