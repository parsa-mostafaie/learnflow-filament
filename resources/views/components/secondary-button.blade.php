@props(['disabled' => false])

<button
  {{ $attributes->merge([
      'type' => 'button',
      'class' => '
              inline-flex items-center gap-2
              px-5 py-2.5
              rounded-xl font-medium text-sm uppercase tracking-wide
              bg-white text-gray-700
              dark:bg-gray-800 dark:text-gray-300
              border border-gray-300 dark:border-gray-500
              shadow-sm hover:shadow-md
              transition-all duration-200 ease-out
              hover:bg-gray-50 dark:hover:bg-gray-700
              focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-offset-2
              dark:focus:ring-indigo-500 dark:focus:ring-offset-gray-900
              disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-300 disabled:dark:bg-gray-700 disabled:text-gray-500
          ',
  ]) }}
  @disabled($disabled)>
  {{ $slot }}
</button>
