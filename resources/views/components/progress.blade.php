@props(['percentage'])
<div class="shadow-md bg-gray-200 rounded-full dark:bg-gray-600 dark:shadow-gray-500 text-center text-gray-500 dark:text-gray-200">
  <div class="bg-purple-600 rounded-full dark:bg-purple-500 text-xs font-medium p-0.5 leading-none" style="width: {{ $percentage }}%">
    {{ intval($percentage) }}%
  </div>
</div>
