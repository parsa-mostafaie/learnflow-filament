<?php

use function Livewire\Volt\{state, form, usesFileUploads, on, mount};
use Illuminate\Support\Str;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Livewire\Forms\QuestionForm;
use Masmerise\Toaster\Toaster;

// Enable file uploads
usesFileUploads();

// Define the state for the component
state(['question' => null]);

// Define the form for the question
form(QuestionForm::class, 'form');

// Define event listeners to handle the edit-question event
on([
    'edit-question' => function ($question_id) {
        $question = Question::findOrFail($question_id);

        $this->authorize('update', $question);

        if ($this->form->setModel($question)) {
            $this->dispatch('open-modal', 'edit-question');
            $this->dispatch('question-update-form-opened');
        }
    },
]);

/**
 * Function to handle the form submission
 */
$submit = function () {
    if (!$this->form->model) {
        return;
    }

    $this->authorize('update', $this->form->model);

    $this->form->save($this->form->model);

    $this->dispatch('close-modal', 'edit-question');
    $this->dispatch('questions-table-reload');
    $this->dispatch('question-updated');
    Toaster::success(__('Saved.'));
};
?>

{{-- Container for the edit question section --}}
<div id="edit-question-section">
  <x-modal name="edit-question" focusable :show="!empty($this->form->model)">
    <div class="p-6">
      <div>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ __('Edit a question') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ __('Edit a question') }}
        </p>
      </div>

      <form wire:submit="submit" class="mt-6 space-y-6" @if (!$this->form->model) inert @endif>
        <div>
          <x-input-label for="question" :value="__('Question Text')" />
          <x-text-input :disabled="!$this->form->model" wire:model="form.question" id="question" class="block mt-1 w-full"
            type="text" name="question" required autofocus autocomplete="question" />
          <x-input-error :messages="$errors->get('form.question')" class="mt-2" />
        </div>

        <div>
          <x-input-label for="answer" :value="__('Answer')" />
          <x-text-input wire:model="form.answer" id="answer" class="block mt-1 w-full" type="text" name="answer"
            autofocus autocomplete="answer" :disabled="!$this->form->model" />
          <x-input-error :messages="$errors->get('form.answer')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
          <x-gradient-button type="button" :disabled="!$this->form->model"
            x-on:click="$dispatch('close-modal', 'edit-question')">{{ __('Cancel') }}</x-gradient-button>
          <x-gradient-button :disabled="!$this->form->model">{{ __('Save') }}</x-gradient-button>
        </div>
      </form>
    </div>
  </x-modal>
</div>
