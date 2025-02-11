<?php
use function Livewire\Volt\{state, computed, on, mount};
use App\Models\Card, App\Events\CourseViewedEvent;
use App\Facades\Leitner;

state(['course', 'in_feed', 'card' => null, 'started' => false]);

on([
    'course-single-reload' => function ($course) {
        if ($course == $this->course->id) {
            $this->course->fresh();
        }
    },
    'knows-card' => function ($card, $state) {
        $card = Card::findOrFail($card);
        Leitner::knowsCard(auth()->user(), $card, $state);
        $this->loadCard();
    },
]);

$loadCard = function () {
    if (!$this->started) {
        $this->course->checkDailyTasks(auth()->user());
        $this->started = true;
    }

    $this->card = Leitner::getFirstToLearnCard($this->course, auth()->id());
};
?>

<div>
  @if ($course->isEnrolledBy(auth()->user()))
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-2">
      <div class="p-6 text-gray-900 dark:text-gray-100">
        <h1 class="text-lg text-center font-bold mb-1">{{ __('Learn') }}</h1>
        <div class="flex justify-center">
          @if ($this->started)
            @if ($this->card)
              <livewire:courses.question-card :card="$this->card" wire:key="{{ $this->card->id }}"/>
            @else
              {{ __("You've Completed your today's learning!") }}
            @endif
          @else
            <x-gradient-button type="button" wire:click="loadCard">{{ __('Start Learning!') }}</x-gradient-button>
          @endif
        </div>
      </div>
    </div>
  @endif
</div>
