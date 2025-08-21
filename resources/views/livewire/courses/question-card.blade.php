@props(['card'])

<?php
use function Livewire\Volt\{state, computed, on, mount};
use App\Enums\Status;

// Define the state for the component
state(['card', 'showAnswer' => false]);

// Define the function to toggle the answer visibility
$toggleAnswer = function () {
    $this->showAnswer = !$this->showAnswer;
};

// Define the function to handle knowing the card
$knowing = function ($state) {
    $this->dispatch('knows-card', $this->card->id, $state);
};
?>

<div class="w-full">
  <div
    class="max-w-lg p-6 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-xl bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-300 mx-auto"
    x-data="{ showAnswerButton: false }" x-init="setTimeout(() => showAnswerButton = true, 5000)">
    @if ($card->courseQuestion->question->isStatus(Status::Approved))
      <div class="flex flex-col items-center gap-4 text-center" dir="auto">
        {{-- Course Link --}}
        <a href="{{ $card->courseQuestion->course->learn_url }}" wire:navigate
          class="group relative inline-block rounded-2xl bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700
          dark:from-purple-600 dark:to-purple-500 text-white px-5 py-3 text-sm sm:text-base font-semibold
          shadow-lg hover:shadow-2xl transform hover:-translate-y-0.5 active:scale-95
          transition-all duration-300 break-all">
          <span>
            <x-heroicon-c-book-open class="size-5 inline-block" />
            {{ $card->courseQuestion->course->title }}
          </span>
          <span
            class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-white rounded-full
            group-hover:w-2/3 transition-all duration-300"></span>
        </a>

        {{-- Question --}}
        <span
          class="text-2xl break-all w-full font-extrabold tracking-tight text-gray-900 dark:text-white select-none leading-snug">
          <livewire:piper-text :text="$card->courseQuestion->question->question" />
        </span>
      </div>

      {{-- Answer Section --}}
      @if ($showAnswer)
        <p
          class="mt-3 mb-4 text-lg break-all text-purple-700 dark:text-purple-400 font-semibold text-center leading-relaxed w-full">
          <livewire:piper-text :text="$card->courseQuestion->question->answer" />
        </p>
        <div class="flex justify-center gap-3">
          <x-primary-button type="button" wire:click="knowing(true)" class="flex items-center gap-2">
            <x-heroicon-c-sparkles class="size-5" />
            {{ __('messages.known') }}
          </x-primary-button>

          <x-danger-button type="button" wire:click="knowing(false)" class="flex items-center gap-2">
            <x-heroicon-c-face-frown class="size-5" />
            {{ __('messages.unknown') }}
          </x-danger-button>
        </div>
      @else
        <div class="flex justify-center mt-4" x-show="showAnswerButton">
          <x-primary-button wire:click="toggleAnswer" class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold">
            <x-heroicon-c-bolt class="size-5" />
            {{ __('messages.show-me-answer') }}
          </x-primary-button>
        </div>
      @endif
    @endif
  </div>
</div>
