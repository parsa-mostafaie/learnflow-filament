@props(['disabled' => false])

<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center px-4 py-2 bg-purple-700
    dark:bg-purple-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-purple-800
    uppercase tracking-widest hover:bg-purple-600 dark:hover:bg-white focus:bg-purple-600 dark:focus:bg-white
    active:bg-purple-800 dark:active:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500
    focus:ring-offset-2 dark:focus:ring-offset-purple-800 transition ease-in-out duration-150'
    ]) }} @if($disabled) disabled @endif>
    {{ $slot }}
</button>
