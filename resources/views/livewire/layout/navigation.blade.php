<?php

use App\Livewire\Actions\Logout;
use function Livewire\Volt\uses;
use Masmerise\Toaster\Toaster;

$logout = function (Logout $logout) {
    $logout();

    Toaster::success(__('Logged Out!'));

    $this->redirect('/', navigate: true);
};

$logout_impersonation = function () {
    if (!app('impersonate')->isImpersonating()) {
        return;
    }

    app('impersonate')->leave();

    Toaster::success(__('Logged Out!'));

    if ($redirectTo = app('impersonate')->getLeaveRedirectTo() != 'back') {
        $this->redirect(app('impersonate')->getLeaveRedirectTo(), navigate: true);
    }
};

?>

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
  {{-- Primary Navigation Menu --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex">
        {{-- Logo --}}
        <div class="shrink-0 flex items-center">
          <a href="{{ route('welcome') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
          </a>
        </div>

        {{-- Navigation Links --}}
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
          @auth
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
              {{ __('navigation.app-layout.dashboard') }}
            </x-nav-link>
            @can('manage some thing')
              <x-nav-link :href="route('filament.panel.pages.dashboard')">
                {{ __('navigation.app-layout.panel') }}
              </x-nav-link>
            @endcan
          @else
            <x-nav-link :href="login_url()">
              {{ __('Login') }}
            </x-nav-link>
            <x-nav-link :href="register_url()" wire:navigate>
              {{ __('Register') }}
            </x-nav-link>
          @endauth
        </div>
      </div>

      {{-- Settings Dropdown --}}
      @if (auth()->check())
        <div class="hidden sm:flex sm:items-center sm:ms-6">
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name">
                </div>

                <div class="ms-1">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
              </button>
            </x-slot>

            <x-slot name="content">
              <x-dropdown-link :href="route('filament.panel.pages.my-profile')">
                {{ __('navigation.app-layout.profile') }}
              </x-dropdown-link>

              {{-- Authentication --}}
              <button wire:click="logout" class="w-full text-start p-0">
                <x-dropdown-link class="!text-red-600 text-bold">
                  {{ __('Log Out') }}
                </x-dropdown-link>
              </button>
              @impersonating($guard = null)
                <button wire:click="logout_impersonation" class="w-full text-start p-0">
                  <x-dropdown-link class="!text-red-600 text-bold">
                    {{ __('Log Out Impersonation') }}
                  </x-dropdown-link>
                </button>
              @endImpersonating
            </x-slot>
          </x-dropdown>
        </div>
      @endif

      {{-- Hamburger --}}
      <div class="-me-2 flex items-center sm:hidden">
        <button @click="open = ! open"
          class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  {{-- Responsive Navigation Menu --}}
  <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
      @auth
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
          {{ __('navigation.app-layout.dashboard') }}
        </x-responsive-nav-link>

        @can('manage some thing')
          <x-responsive-nav-link :href="route('filament.panel.pages.dashboard')">
            {{ __('navigation.app-layout.panel') }}
          </x-responsive-nav-link>
        @endcan
      @else
        <x-responsive-nav-link :href="login_url()">
          {{ __('Login') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="register_url()" wire:navigate>
          {{ __('Register') }}
        </x-responsive-nav-link>
      @endauth
    </div>

    @auth
      {{-- Responsive Settings Options --}}
      <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
        <div class="px-4">
          <div class="font-medium text-base text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
            x-on:profile-updated.window="name = $event.detail.name"></div>
          <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
        </div>

        <div class="mt-3 space-y-1">
          <x-responsive-nav-link :href="route('filament.panel.pages.my-profile')">
            {{ __('navigation.app-layout.profile') }}
          </x-responsive-nav-link>

          {{-- Authentication --}}
          <button wire:click="logout" class="w-full text-start">
            <x-responsive-nav-link class="!text-red-600 text-bold">
              {{ __('Log Out') }}
            </x-responsive-nav-link>
          </button>
          @impersonating($guard = null)
            <button wire:click="logout_impersonation" class="w-full text-start">
              <x-responsive-nav-link class="!text-red-600 text-bold">
                {{ __('Log Out Impersonation') }}
              </x-responsive-nav-link>
            </button>
          @endImpersonating
        </div>
      </div>
    @endauth
  </div>
</nav>
