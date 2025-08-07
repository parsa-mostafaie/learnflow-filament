<?php

use function Livewire\Volt\{state, computed, on, mount};
use App\Models\Card;
use App\Events\CourseViewedEvent;
use App\Facades\Leitner;

// Define the state for the component
state(['course', 'in_feed', 'card' => null, 'started' => false]);

// Define event listeners to handle course reload and card updates
on([
    'course-single-reload' => function ($course) {
        if ($course == $this->course->id) {
            $this->course->fresh();
        }
    },
    'knows-card' => function ($card, $state) {
        $card = Card::findOrFail($card);
        Leitner::knowsCard(auth()->user(), $card, $state);

        if ($state) {
            $rand = rand(0, 7);
            Toaster::success(__("know.good.{$rand}"));
        } else {
            $rand = rand(0, 5);
            Toaster::error(__("know.bad.{$rand}"));
        }

        $this->loadCard();
    },
]);

// Define the loadCard function to load the first card to learn
$loadCard = function () {
    if (!$this->started) {
        $this->course->checkDailyTasks(auth()->user());
        $this->started = true;
    }

    $this->card = Leitner::getFirstToLearnCard($this->course, auth()->id());

    if (!$this->card) {
        Toaster::success(__('messages.learn-finished'));
    }
};

$percentage = computed(fn() => Leitner::getLearnedPercentage($this->course, auth()->user()));
?>

{{-- Learning Section --}}
<div>
  @if ($course->isEnrolledBy(auth()->user()) && Gate::allows('view', $course))
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-2">
      <div class="p-6 text-gray-900 dark:text-gray-100">
        <h1 class="text-lg text-center font-bold mb-1">{{ __('messages.learn') }}</h1>
        <div class="my-2 container mx-auto">
          <div class="mx-auto max-w-lg">
            <x-progress :percentage="$this->percentage" />
          </div>
        </div>
        <div class="flex justify-center">
          @if ($this->percentage != 100)
            @if ($this->started)
              @if ($this->card)
                <livewire:courses.question-card :card="$this->card" wire:key="card-{{ $this->card->id }}" />
              @else
                {{ __("messages.learn-finished") }}
              @endif
            @else
              <x-gradient-button type="button" wire:click="loadCard">{{ __('messages.start-learning') }}</x-gradient-button>
            @endif
          @else
            {{ __("You've Completed this course!") }}
          @endif
        </div>
      </div>
    </div>
  @endif
</div>
