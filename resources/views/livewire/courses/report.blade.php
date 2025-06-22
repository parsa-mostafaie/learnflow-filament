@props(['course', 'user'])

<?php
use function Livewire\Volt\{state, computed};
use Illuminate\Support\Number;
use App\Facades\Leitner;

state(['course', 'user']);

$count = function ($stage, $sub) {
    return Leitner::getCardsInASubbox($this->course_model, $this->user, $stage, $sub);
};

$list = function ($stage, $sub) {
    return Leitner::listCardsInASubbox($this->course_model, $this->user, $stage, $sub);
};

$course_model = computed(function () {
    $model = \App\Models\Course::withTrashed()->findOrFail($this->course);

    if (!Gate::allows('getReport', $model)) {
        abort(404);
    }

    return $model;
});
?>

<div>
  <h1 class="mb-8 text-gray-900 text-3xl font-bold text-center">
    {{ __(':name\'s Report', ['name' => $this->course_model->title]) }}
  </h1>

  <div class="flex flex-wrap justify-center gap-5 max-w-[1200px] mx-auto">
    <!-- Not Imported Box -->
    <x-shematic.notimported-box
      class="w-[300px] h-[250px] flex flex-col justify-between p-5 rounded-lg bg-gradient-to-br from-[#ffded5] to-[#ffc2b5] shadow-lg">
      <h2 class="text-2xl mb-2 text-[#333] text-center">{{ __('Not Imported') }}</h2>
      <div class="flex flex-wrap justify-center gap-2">
        <x-shematic.disabled-subbox>{{ Number::format(Leitner::countNotImportedCards($this->course_model, $this->user)) }}</x-shematic.disabled-subbox>
      </div>
    </x-shematic.notimported-box>

    @foreach (range(1, 5) as $stage)
      <x-shematic.box
        class="w-[300px] h-[250px] flex flex-col justify-between p-5 rounded-lg bg-gradient-to-br from-[#e3e3e3] to-[#ffffff] shadow-lg">
        <h2 class="text-2xl mb-2 text-[#333] text-center">{{ __('Box') }} {{ Number::format($stage) }}</h2>
        <div class="flex flex-wrap justify-center gap-2">
          @foreach (range(1, Leitner::getReviewWait($stage)) as $sub)
            <button wire:click="dispatch('open-modal', 'in-stage-modal-{{ $stage }}-{{ $sub }}')">
              <x-shematic.subbox>{{ Number::format($this->count($stage, $sub)) }}</x-shematic.subbox>
            </button>
          @endforeach
        </div>
      </x-shematic.box>
    @endforeach

    <!-- Completed Box -->
    <x-shematic.completed-box
      class="w-[300px] h-[250px] flex flex-col justify-between p-5 rounded-lg bg-gradient-to-br from-[#d5ffd5] to-[#c2ffc2] shadow-lg">
      <h2 class="text-2xl mb-2 text-[#333] text-center">{{ __('Completed') }}</h2>
      <div class="flex flex-wrap justify-center gap-2">
        <x-shematic.disabled-subbox>{{ Number::format(Leitner::countCompletedCards($this->course_model, $this->user)) }}</x-shematic.disabled-subbox>
      </div>
    </x-shematic.completed-box>
  </div>

  @foreach (range(1, 5) as $stage)
    @foreach (range(1, Leitner::getReviewWait($stage)) as $sub)
      <livewire:modals.in-stage-modals :$stage :$sub :list="$this->list($stage, $sub)" />
    @endforeach
  @endforeach
</div>
