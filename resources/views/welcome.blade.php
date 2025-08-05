<!DOCTYPE html>
<html dir="{{ __('ltr') }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
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
  <meta name="description" content="{{ __('seo.landing.description', ['name' => __(config('app.name', 'LearnFlow'))]) }}">
  <meta name="keywords" content="{{ implode(', ', __('seo.landing.keywords')) }}">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="canonical" href="{{ url()->current() }}">
  <meta property="og:title" content="{{ __(config('app.name', 'LearnFlow')) }}">
  <meta property="og:description"
    content="{{ __('seo.landing.description', ['name' => __(config('app.name', 'LearnFlow'))]) }}">
  <meta property="og:type" content="website">
  <meta property="og:image" content="{{ asset('banner.jpg') }}">
  <meta property="og:site_name" content="{{ __(config('app.name', 'LearnFlow')) }}">
  <meta property="og:see_also" content="{{ url('/') }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta name="robots" content="index, follow">
  <meta http-equiv="Content-Language" content="{{ str_replace('_', '-', app()->getLocale()) }}">
  <meta name="rights" content="{{ __('seo.rights') }}">
  <meta name="application-name" content="{{ config('app.name', 'LearnFlow') }}">
  <meta name="theme-color" content="#6b46c1">

  <title>{{ __(config('app.name', 'LearnFlow')) }}</title>

  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

  {{-- Fonts --}}
  <x-fonts />

  {{-- Styles --}}
  @filamentStyles
  @filamentScripts
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans dark:bg-gray-900 dark:text-white overflow-x-clip">
  {{-- Hero Section --}}
  <header class="header-section bg-purple-500 text-white text-center py-20 container mx-auto dark:bg-gray-800">
    <h2 class="text-4xl font-bold mb-4">{{ __('messages.welcome', ['name' => __(config('app.name', 'LearnFlow'))]) }}
    </h2>
    <p class="text-xl mb-6">
      {{ __('messages.overview') }}
    </p>
    <a href="{{ login_url() }}">
      <x-primary-button type="button">
        <x-heroicon-c-play class="me-2 w-5 h-5" />
        {{ __('messages.get-started') }}
      </x-primary-button>
    </a>
  </header>

  {{-- About Section --}}
  <section id="about" class="container mx-auto p-8">
    <h2 class="text-3xl font-semibold mb-4">{{ __('landing.headings.about') }}</h2>
    <p>
      {{ __('landing.paragraphs.about', ['name' => __(config('app.name', 'LearnFlow'))]) }}
    </p>
  </section>

  {{-- Features Section --}}
  <section id="features" class="container mx-auto bg-gray-200 p-8 dark:bg-gray-700">
    <div>
      <h2 class="text-3xl font-semibold mb-4">{{ __('landing.headings.features') }}</h2>
      <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {{-- Feature 1 --}}
        <livewire:feature-card :title="__('landing.features.A.header')" modal="interactive-exercises" :text="__('landing.features.A.text')" />
        {{-- Feature 2 --}}
        <livewire:feature-card :title="__('landing.features.B.header')" modal="daily-streaks" :text="__('landing.features.B.text')" />
        {{-- Feature 3 --}}
        <div class="bg-white p-6 rounded shadow scale-on-hover flex flex-col dark:bg-gray-800">
          <h3 class="text-xl font-bold mb-2">{{ __('landing.features.C.header') }}</h3>
          <p class="mb-4">
            {{ __('landing.features.C.text') }}
          </p>
          <div class="mt-auto text-center">
            <a href="https://en.wikipedia.org/wiki/Leitner_system" target="_blank"
              class="text-purple-700 hover:underline dark:text-purple-500">{{ __('messages.learn-more') }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Contact Section --}}
  <section id="contact" class="container mx-auto p-8">
    <h2 class="text-3xl font-semibold mb-4">{{ __('landing.contact.us') }}</h2>
    <div class="grid gap-4 grid-cols-1 md:grid-cols-2 gap-6">
      <form class="grid gap-6" action="mailto:pmostafaie1390@gmail.com" method="post" enctype="text/plain">
        <x-text-input name="name" type="text" placeholder="{{ __('landing.contact.name') }}"
          class="input dark:bg-gray-700 dark:text-white" autocomplete="name" />
        <x-text-input name="mail" type="email" placeholder="{{ __('landing.contact.email') }}"
          class="input dark:bg-gray-700 dark:text-white" />
        <x-text-area name="message" placeholder="{{ __('landing.contact.message') }}"
          class="input h-32 dark:bg-gray-700 dark:text-white"></x-text-area>
        <x-primary-button type="submit" class="justify-center">
          <x-heroicon-c-paper-airplane class="me-2 w-5 h-5" />
          {{ __('landing.contact.send') }}
        </x-primary-button>
      </form>
      <div
        class="contact-info shadow-lg p-4 bg-[#f7fafc] rounded-lg md:rotate-[10deg] md:translate-x-4 md:translate-y-2 dark:bg-gray-800">
        <h3 class="text-xl font-bold mb-3">{{ __('landing.contact.developer.title') }}</h3>
        <div class="flex gap-1 flex-wrap">
          <x-primary-button type="submit"><x-icon-linkedin class="w-5 h-5 me-2" />
            <a href="https://www.linkedin.com/in/parsa-mostafaie">{{ __('landing.contact.developer.linkedin') }}</a>
          </x-primary-button>
          <x-primary-button type="submit"><x-icon-github class="w-5 h-5 me-2" />
            <a href="https://github.com/parsa-mostafaie">{{ __('landing.contact.developer.github') }}</a>
          </x-primary-button>
          <x-primary-button type="submit"><x-heroicon-c-phone class="me-2 w-5 h-5" />
            <a href="tel:+989056372307">{{ __('landing.contact.developer.phone') }}</a>
          </x-primary-button>
        </div>
      </div>
    </div>
  </section>

  {{-- Footer --}}
  <footer
    class="bg-purple-700 text-white p-4 text-center container mx-auto rounded-lg dark:bg-gray-800 dark:text-white">
    <p>&copy; {{ __('landing.footer.year') }} {{ __(config('app.name', 'LearnFlow')) }}:
      {{ __('landing.footer.text') }}
      {{ __('landing.footer.rights') }}</p>
      <livewire:text-to-speech :text="__('landing.footer.text')"
        class="mt-4 max-w-md mx-auto bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg"></livewire:text-to-speech>
  </footer>

  <x-theme-toggler />

  <livewire:modals.daily-streaks />
  <livewire:modals.interactive-exercises />
  @persist('toaster')
    <x-toaster-hub />
  @endpersist
  @livewire('notifications')

  <x-impersonate::banner />
</body>

</html>
