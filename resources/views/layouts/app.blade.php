<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ __(key: 'ltr') }}" x-data="{
    theme: localStorage.getItem('theme') || 'system',
    get isDark() {
        return this.theme === 'dark' ||
            (this.theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
    }
}"
  x-init="$watch('theme', val => localStorage.setItem('theme', val))"  x-bind:class="{ 'dark': isDark }">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ __(config('app.name', 'LearnFlow')) }}</title>
  {{-- <link rel="icon"
    href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💡</text></svg>"> --}}
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  {{-- Scripts --}}
  @filamentStyles
  @filamentScripts
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" x-bind:class="{ 'dark': isDark }">
  <div class="min-h-screen bg-purple-100 dark:bg-purple-700">
    {{-- Livewire navigation component --}}
    <livewire:layout.navigation />

    {{-- Page Heading --}}
    @if (isset($header))
      <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endif

    {{-- Page Content --}}
    <main>
      {{ $slot }}
    </main>
  </div>

  {{-- Theme toggler component --}}
  <x-theme-toggler />
  <x-speed-dial />

  @persist('toaster')
    <x-toaster-hub />
  @endpersist
  @livewire('notifications')
</body>

</html>
