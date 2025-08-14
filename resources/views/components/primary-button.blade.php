@props(['disabled' => false])

<button 
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => '
            inline-flex items-center gap-2
            px-5 py-2.5 
            rounded-xl font-medium text-sm uppercase tracking-wide
            bg-purple-600 text-white
            dark:bg-purple-300 dark:text-purple-900
            shadow-sm hover:shadow-md
            transition-all duration-200 ease-out
            hover:bg-purple-500 dark:hover:bg-purple-200
            focus:outline-none focus:ring-4 focus:ring-purple-400 focus:ring-offset-2
            dark:focus:ring-purple-500 dark:focus:ring-offset-purple-900
            disabled:opacity-50 disabled:cursor-not-allowed
        '
    ]) }} 
    @disabled($disabled)
>
    {{ $slot }}
</button>
