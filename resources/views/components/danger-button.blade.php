@props(['disabled' => false])

<button
  {{ $attributes->merge([
      'type' => 'submit',
      'class' => '
              inline-flex items-center gap-2
              px-5 py-2.5
              rounded-xl font-medium text-sm uppercase tracking-wide
              bg-red-600 text-white
              dark:bg-red-400 dark:text-red-900
              border border-red-600 dark:border-red-400
              shadow-sm hover:shadow-md
              transition-all duration-200 ease-out
              hover:bg-red-500 dark:hover:bg-red-300
              hover:border-red-500 dark:hover:border-red-300
              focus:outline-none focus:ring-4 focus:ring-red-400 focus:ring-offset-2
              dark:focus:ring-red-500 dark:focus:ring-offset-gray-900
              disabled:opacity-50 disabled:cursor-not-allowed
          ',
  ]) }}
  @disabled($disabled)>
  {{ $slot }}
</button>
