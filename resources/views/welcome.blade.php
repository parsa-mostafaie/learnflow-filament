<!DOCTYPE html>
<html dir="{{ __('ltr') }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }"
  x-bind:class="{ 'dark': darkMode }" x-init="if (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      localStorage.setItem('darkMode', 'true');
  }
  darkMode = JSON.parse(localStorage.getItem('darkMode'));
  $watch('darkMode', val => localStorage.setItem('darkMode', JSON.stringify(val)))">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Deeplearn') }}</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  {{-- Styles --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans dark:bg-gray-900 dark:text-white overflow-x-clip">
  {{-- Hero Section --}}
  <header class="header-section text-white text-center py-20 container mx-auto dark:bg-gray-800">
    <h2 class="text-4xl font-bold mb-4">{{ __('Welcome to :name', ['name' => config('app.name', 'Deeplearn')]) }}</h2>
    <p class="text-xl mb-6">
      {{ __('The ultimate platform to learn anything efficiently and effectively using the Leitner box algorithm') }}
    </p>
    <a href="{{ route('login') }}" wire:navigate>
      <x-primary-button type="button">
        <i class="fas fa-play me-2"></i> {{ __('Get Started') }}
      </x-primary-button>
    </a>
  </header>

  {{-- About Section --}}
  <section id="about" class="container mx-auto p-8">
    <h2 class="text-3xl font-semibold mb-4">{{ __('About Us') }}</h2>
    <p>
      {{ __('At :name, we are dedicated to providing top-notch learning resources tailored to your needs. Our platform is designed to be user-friendly, interactive, and engaging, ensuring you have the best learning experience possible.', ['name' => config('app.name', 'Deeplearn')]) }}
    </p>
  </section>

  {{-- Features Section --}}
  <section id="features" class="container mx-auto bg-gray-200 p-8 dark:bg-gray-700">
    <div>
      <h2 class="text-3xl font-semibold mb-4">{{ __('Our Features') }}</h2>
      <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {{-- Feature 1 --}}
        <div class="bg-white p-6 rounded shadow scale-on-hover flex flex-col dark:bg-gray-800">
          <h3 class="text-xl font-bold mb-2">{{ __('Daily Streaks') }}</h3>
          <p class="mb-4">
            {{ __('Maintain your learning momentum by achieving daily streaks. Stay consistent and see your progress soar!') }}
          </p>
          <div class="mt-auto text-center">
            <a href="#" class="text-purple-700 hover:underline dark:text-purple-500">{{ __('Learn More') }}</a>
          </div>
        </div>
        {{-- Feature 2 --}}
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
        {{-- Feature 3 --}}
        <div class="bg-white p-6 rounded shadow scale-on-hover flex flex-col dark:bg-gray-800">
          <h3 class="text-xl font-bold mb-2">{{ __('Interactive Exercises') }}</h3>
          <p class="mb-4">{{ __('Engage with interactive exercises that adapt to your learning pace and style.') }}
          </p>
          <div class="mt-auto text-center">
            <a href="#" class="text-purple-700 hover:underline dark:text-purple-500">{{ __('Learn More') }}</a>
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
        <p>{{ __('Or contact with the developer from:') }}</p>
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
    <p>&copy; {{ __('2025') }} {{ __(config('app.name', 'Deeplearn')) }}: {{ __('Deep learn anything.') }}
      {{ __('All rights reserved.') }}</p>
  </footer>

  @persist('toaster')
    <x-toaster-hub />
  @endpersist
</body>

</html>
