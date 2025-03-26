@props(['question', 'selected'])

<li class="px-4 py-4 flex justify-between items-center">
  <div class="flex items-center">
    <input type="checkbox" id="ci_question_{{ $question['id'] }}" wire:key="ci_question_{{ $question['id'] }}"
      wire:model="{{ $selected }}" value="{{ $question['id'] }}"
      class="h-4 w-4 text-blue-600 border-gray-300 rounded me-3">
    <span class="text-gray-900 dark:text-gray-300">
      {{ $question['question'] }}: {{ $question['answer'] }}
    </span>
  </div>
  <x-status-icon :status="$question['status']" />
</li>
