@props(['disabled' => false])

<textarea @disabled($disabled)
  {{ $attributes->merge([
      'class' =>
          'bg-[#fff] border-purple-200 dark:border-purple-300 dark:bg-purple-300 dark:text-purple-100 focus:border-purple-400 dark:focus:border-purple-400 focus:ring-2 focus:ring-purple-400 dark:focus:ring-purple-500 focus:outline-none rounded-md shadow-sm px-4 py-2',
  ]) }}></textarea>
