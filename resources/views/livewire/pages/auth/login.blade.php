<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    Toaster::success(__('Logged in!'));

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>

<div>
  {{-- Session Status --}}
  <x-auth-session-status class="mb-4" :status="session('status')" />

  <form wire:submit="login">
    {{-- Email Address --}}
    <div>
      <x-input-label for="email" :value="__('users.columns.email')" />
      <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required
        autofocus autocomplete="username" />
      <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
    </div>

    {{-- Password --}}
    <div class="mt-4">
      <x-input-label for="password" :value="__('Password')" />

      <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password" name="password"
        required autocomplete="current-password" />

      <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
    </div>

    {{-- Remember Me --}}
    <div class="block mt-4">
      <label for="remember" class="inline-flex items-center">
        <input wire:model="form.remember" id="remember" type="checkbox"
          class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
          name="remember">
        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
      </label>
    </div>

    <div class="flex items-center justify-between mt-4">
      <div class="flex flex-col justify-center items-center">
        @if (Route::has('password.request'))
          <a class="link" href="{{ route('password.request') }}" wire:navigate>
            {{ __('Forgot your password?') }}
          </a>
          <a class="link" href="{{ register_url() }}" wire:navigate>
            {{ __('Don\'t have any account?') }}
          </a>
        @endif
      </div>

      <x-primary-button class="ms-3">
        {{ __('Log in') }}
      </x-primary-button>
    </div>
  </form>
</div>
