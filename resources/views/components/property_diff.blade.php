@props(['data'])

<div>
  {{-- Combine keys from both "old" and "attributes" --}}
  @foreach (array_unique(array_merge(array_keys($data['old'] ?? []), array_keys($data['attributes'] ?? []))) as $key)
    <div class="rounded p-2 mb-4 opacity-90 
                bg-gray-100 dark:bg-gray-800">
      {{-- Property name --}}
      <strong class="block mb-2 text-gray-800 dark:text-gray-200">
        {{ __('properties.' . $key) }}:
      </strong>

      {{-- Flex container for old and current values --}}
      <div class="flex gap-4">
        {{-- Old Value (Red Badge with Line-Through) --}}
        @if (isset($data['old'][$key]))
          <span
            class="inline-block bg-red-100 dark:bg-red-700 
                        rounded-lg px-3 py-1 line-through
                        text-red-800 dark:text-red-200 text-sm">
            {{ $data['old'][$key] }}
          </span>
        @endif

        {{-- Current Value (Green Badge) --}}
        @if (isset($data['attributes'][$key]))
          <span
            class="inline-block bg-green-100 dark:bg-green-700 
                        rounded-lg px-3 py-1 
                        text-green-800 dark:text-green-200 text-sm">
            {{ $data['attributes'][$key] }}
          </span>
        @endif

        {{-- No Old or New Value --}}
        @if (!isset($data['old'][$key]) && !isset($data['attributes'][$key]))
          <span class="italic text-gray-600 dark:text-gray-400 text-sm">
            {{ __('messages.empty') }}
          </span>
        @endif
      </div>
    </div>
  @endforeach
</div>
