@props(['percentage'])
<div class="mx-auto md:w-[50%] bg-gray-200 rounded-full dark:bg-gray-700 text-center">
  <div class="bg-purple-600 rounded-full dark:bg-purple-500 text-xs font-medium p-0.5 leading-none" style="width: {{ $percentage }}%">
    {{ intval($percentage) }}%
  </div>
</div>
