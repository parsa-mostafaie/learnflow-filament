@props(['on', 'message'])

<div x-data="{ shown: false, timeout: null }" x-init="@this.on('{{ $on }}', () => {
    clearTimeout(timeout);
    shown = true;
    Toaster.success(@js($message ?: __('messages.saved')));
    timeout = setTimeout(() => { shown = false }, 2000);
})" x-show.transition.out.opacity.duration.1500ms="shown"
  x-transition:leave.opacity.duration.1500ms style="display: none;"
  {{ $attributes->merge(['class' => 'text-sm text-gray-600 dark:text-gray-400']) }}>
  {{ $slot->isEmpty() ? __('messages.saved') : $slot }}
</div>
