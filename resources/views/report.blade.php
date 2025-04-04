<!DOCTYPE html>
<html dir="{{ __(key: 'ltr') }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f5f5f5] p-5">
  <livewire:courses.report :user="Auth::user()" :course="$id" />
</body>

</html>
