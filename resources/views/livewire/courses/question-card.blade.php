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
    class="max-w-lg p-4 border border-gray-200 rounded-2xl shadow-lg bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 dark:border-gray-700 height-100 container"
    x-data="{ showAnswerButton: false }" x-init="setTimeout(() => showAnswerButton = true, 5000)">
    @if ($card->courseQuestion->question->isStatus(Status::Approved))
      <div class="flex justify-between items-center sm:flex-row flex-col-reverse gap-2 flex-wrap-reverse" dir="auto">
        <div class="flex justify-center flex-1">
          <span class="text-center text-2xl inline-block font-bold tracking-tight text-gray-900 dark:text-white select-none">
            {{ $card->courseQuestion->question->question }}
          </span>
        </div>

        <div class="flex justify-center flex-1">
          <div class="text-center rounded-xl bg-purple-600 dark:bg-purple-500 text-gray-100 dark:text-gray-200 p-2 text-sm">
            {{ $card->courseQuestion->course->title }}
          </div>
        </div>
      </div>

      @if ($showAnswer)
        <p class="mb-3 text-md text-purple-700 dark:text-purple-400 font-bold mt-2 text-center">
          {{ $card->courseQuestion->question->answer }}</p>
        <div class="flex justify-center">
          <x-button-group>
            <x-primary-button type="button" wire:click="knowing(true)">{{ __('messages.known') }}</x-primary-button>
            <x-danger-button type="button" wire:click="knowing(false)">{{ __('messages.unknown') }}</x-danger-button>
          </x-button-group>
        </div>
      @else
        <div class="flex justify-center">
          <x-primary-button class="mt-2" type="button" x-show="showAnswerButton"
            wire:click="toggleAnswer">{{ __('messages.show-me-answer') }}</x-primary-button>
        </div>
      @endif
    @endif
  </div>
  <hr class="my-2" />
  <livewire:text-to-speech :text="$card->courseQuestion->question->question" class="mb-4" />
</div>
