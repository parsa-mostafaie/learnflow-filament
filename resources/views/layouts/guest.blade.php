<!DOCTYPE html>
<html dir="{{ __('ltr') }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }"
  x-init="if (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      localStorage.setItem('darkMode', 'true');
  }
  darkMode = JSON.parse(localStorage.getItem('darkMode'));
  $watch('darkMode', val => localStorage.setItem('darkMode', JSON.stringify(val)));">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ __(config('app.name', 'LearnFlow')) }}</title>
  <link rel="icon"
    href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💡</text></svg>">


  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  {{-- Scripts --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased" x-bind:class="{ 'dark': darkMode }">
  {{-- Main container --}}
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-purple-100 dark:bg-purple-900">
    {{-- Application logo --}}
    <div>
      <a href="/" wire:navigate>
        <x-application-logo class="w-20 h-20 fill-current text-purple-500 dark:text-purple-300" />
      </a>
    </div>

    {{-- Page content --}}
    <div
      class="w-full sm:max-w-md mt-6 px-6 py-4 bg-purple-50 dark:bg-purple-800 shadow-md overflow-hidden sm:rounded-lg">
      {{ $slot }}
    </div>
  </div>

  @persist('toaster')
    <x-toaster-hub />
  @endpersist
</body>

</html>
