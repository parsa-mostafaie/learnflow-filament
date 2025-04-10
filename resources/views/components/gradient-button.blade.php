@props(['disabled' => false])

<button 
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'relative inline-flex items-center justify-center p-0.5 mb-2 overflow-hidden text-sm font-medium rounded-lg group focus:ring-4 focus:outline-none transition-all ease-in duration-75 bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-blue-300 dark:focus:ring-blue-800 disabled:bg-gray-400 disabled:text-gray-700 disabled:opacity-70 disabled:cursor-not-allowed'
    ]) }}
    @if ($disabled) disabled @endif
>
    <span
        class="relative px-5 py-2.5 bg-white dark:bg-gray-900 rounded-md transition-all ease-in duration-75 group-hover:bg-transparent group-hover:dark:bg-transparent disabled:bg-gray-300 disabled:dark:bg-gray-800">
        {{ $slot }}
    </span>
</button>