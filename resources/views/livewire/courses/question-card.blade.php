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

<div
  class="max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 height-100 container"
  x-data="{ showAnswerButton: false }" x-init="setTimeout(() => showAnswerButton = true, 5000)">
  @if ($card->courseQuestion->question->isStatus(Status::Approved))
    <div class="flex justify-between sm:flex-row flex-col gap-2 flex-wrap-reverse sm:justify-center items-center"
      dir="auto">
      <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white select-none me-auto">
        {{ $card->courseQuestion->question->question }}
      </h5>
      <div class="rounded-lg bg-purple-700 dark:bg-purple-500 text-gray-200 p-2">
        {{ $card->courseQuestion->course->title }}
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
      <x-primary-button class="mt-2" type="button" x-show="showAnswerButton"
        wire:click="toggleAnswer">{{ __('messages.show-me-answer') }}!</x-primary-button>
    @endif
  @endif
</div>
