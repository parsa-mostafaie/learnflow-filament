<!DOCTYPE html>
<html dir="{{ __(key: 'ltr') }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="noindex, nofollow">

  <title>{{ __(config('app.name', 'LearnFlow')) }}</title>

  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

  {{-- Fonts --}}
  <x-fonts />

  {{-- Scripts --}}
  @filamentStyles
  @filamentScripts
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f5f5f5] p-5 font-sans">
  <livewire:courses.report :user="Auth::user()" :course="$id" />

  <x-impersonate::banner />
</body>

</html>
