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

  <title>{{ __(config('app.name', 'LearnFlow')) }}</title>
  {{-- <link rel="icon"
    href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💡</text></svg>"> --}}
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  {{-- Styles --}}
  @filamentStyles
  @filamentScripts
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans dark:bg-gray-900 dark:text-white overflow-x-clip">
  {{-- Hero Section --}}
  <header class="header-section text-white text-center py-20 container mx-auto dark:bg-gray-800">
    <h2 class="text-4xl font-bold mb-4">{{ __('Welcome to :name', ['name' => __(config('app.name', 'LearnFlow'))]) }}
    </h2>
    <p class="text-xl mb-6">
      {{ __('The ultimate platform to learn anything efficiently and effectively using the Leitner box algorithm') }}
    </p>
    <a href="{{ login_url() }}">
      <x-primary-button type="button">
        <i class="fas fa-play me-2"></i> {{ __('Get Started') }}
      </x-primary-button>
    </a>
  </header>

  {{-- About Section --}}
  <section id="about" class="container mx-auto p-8">
    <h2 class="text-3xl font-semibold mb-4">{{ __('About Us') }}</h2>
    <p>
      {{ __('At :name, we are dedicated to providing top-notch learning resources tailored to your needs. Our platform is designed to be user-friendly, interactive, and engaging, ensuring you have the best learning experience possible.', ['name' => __(config('app.name', 'LearnFlow'))]) }}
    </p>
  </section>

  {{-- Features Section --}}
  <section id="features" class="container mx-auto bg-gray-200 p-8 dark:bg-gray-700">
    <div>
      <h2 class="text-3xl font-semibold mb-4">{{ __('Our Features') }}</h2>
      <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {{-- Feature 1 --}}
        <livewire:feature-card :title="__('Interactive Exercises')" modal="interactive-exercises" :text="__('Engage with interactive exercises that adapt to your learning pace and style.')" />
        {{-- Feature 2 --}}
        <livewire:feature-card :title="__('Daily Streaks')" modal="daily-streaks" :text="__(
            'Maintain your learning momentum by achieving daily streaks. Stay consistent and see your progress soar!',
        )" />
        {{-- Feature 3 --}}
        <div class="bg-white p-6 rounded shadow scale-on-hover flex flex-col dark:bg-gray-800">
          <h3 class="text-xl font-bold mb-2">{{ __('Leitner System') }}</h3>
          <p class="mb-4">
            {{ __('Utilize the efficient Leitner system to ensure you retain and recall what you learn effectively.') }}
          </p>
          <div class="mt-auto text-center">
            <a href="https://en.wikipedia.org/wiki/Leitner_system" target="_blank"
              class="text-purple-700 hover:underline dark:text-purple-500">{{ __('Learn More') }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Contact Section --}}
  <section id="contact" class="container mx-auto p-8">
    <h2 class="text-3xl font-semibold mb-4">{{ __('Contact Us') }}</h2>
    <div class="grid gap-4 grid-cols-1 md:grid-cols-2 gap-6">
      <form class="grid gap-6" action="mailto:pmostafaie1390@gmail.com" method="post" enctype="text/plain">
        <x-text-input type="text" placeholder="{{ __('Your Name') }}"
          class="input dark:bg-gray-700 dark:text-white" />
        <x-text-input type="email" placeholder="{{ __('Your Email') }}"
          class="input dark:bg-gray-700 dark:text-white" />
        <x-text-area placeholder="{{ __('Your Message') }}"
          class="input h-32 dark:bg-gray-700 dark:text-white"></x-text-area>
        <x-primary-button type="submit" class="justify-center">
          <i class="fas fa-paper-plane me-2"></i> {{ __('Send Message') }}
        </x-primary-button>
      </form>
      <div
        class="contact-info shadow-lg p-4 bg-[#f7fafc] rounded-lg md:rotate-[10deg] md:translate-x-4 md:translate-y-2 dark:bg-gray-800">
        <h3 class="text-xl font-bold mb-3">{{ __('Contact with Developer') }}</h3>
        <div class="flex gap-1 flex-wrap">
          <x-primary-button type="submit"><i class="fab fa-linkedin me-2"></i>
            <a href="https://www.linkedin.com/in/parsa-mostafaie">{{ __('LinkedIn') }}</a>
          </x-primary-button>
          <x-primary-button type="submit"><i class="fab fa-github me-2"></i>
            <a href="https://github.com/parsa-mostafaie">{{ __('GitHub') }}</a>
          </x-primary-button>
          <x-primary-button type="submit"><i class="fa fa-phone me-2"></i>
            <a href="tel:+989056372307">{{ __('Phone') }}</a>
          </x-primary-button>
        </div>
      </div>
    </div>
  </section>

  {{-- Footer --}}
  <footer
    class="bg-purple-700 text-white p-4 text-center container mx-auto rounded-lg dark:bg-gray-800 dark:text-white">
    <p>&copy; {{ __('2025') }} {{ __(config('app.name', 'LearnFlow')) }}: {{ __('In-Depth learn anything.') }}
      {{ __('All rights reserved.') }}</p>
  </footer>

  <x-theme-toggler />

  <livewire:modals.daily-streaks />
  <livewire:modals.interactive-exercises />
  @persist('toaster')
    <x-toaster-hub />
  @endpersist

  <x-impersonate::banner />
</body>

</html>
