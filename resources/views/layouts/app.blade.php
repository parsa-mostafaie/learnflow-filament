@props(['robots' => '', 'title' => '', 'canonical'])

@php
  $fullTitle = (filled($title) ? "$title - " : null) . __(config('app.name', 'LearnFlow'));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ __(key: 'ltr') }}" x-data="{
    theme: localStorage.getItem('theme') || 'system',
    get isDark() {
        return this.theme === 'dark' ||
            (this.theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
    }
}"
  x-init="$watch('theme', val => localStorage.setItem('theme', val))" x-bind:class="{ 'dark': isDark }">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="Content-Language" content="{{ str_replace('_', '-', app()->getLocale()) }}">
  <meta name="rights" content="{{ __('seo.rights') }}">
  <meta name="application-name" content="{{ config('app.name', 'LearnFlow') }}">
  <meta name="theme-color" content="#6b46c1">
  <meta name="robots" content="{{ $robots }}index, {{ $robots }}follow">
  <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
  <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
  @if (isset($meta))
    {{ $meta }}
  @endif
  <title>{{ $fullTitle }}</title>
  <meta property="og:title" content="{{ $fullTitle }}">
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

  {{-- Fonts --}}
  <x-fonts />

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

  <x-impersonate::banner />
</body>

</html>
