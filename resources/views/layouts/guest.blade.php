<!DOCTYPE html>
<html dir="{{ __('ltr') }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ __(config('app.name', 'LearnFlow')) }}</title>

  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

  {{-- Fonts --}}
  <x-fonts />

  {{-- Scripts --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
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
