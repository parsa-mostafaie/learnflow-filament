<?php

use function Livewire\Volt\{state, form, usesFileUploads};

use Illuminate\Support\Str;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\QuestionForm;

// usesFileUploads();

form(QuestionForm::class);

$submit = function () {
    $this->authorize('create', Question::class);

    $this->form->save();

    $this->dispatch('question-stored');
    $this->dispatch('questions-table-reload');
}; ?>

<section class="m-2 mx-3">
    <div>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add a question') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Create a new question') }}
        </p>
    </div>

    <form wire:submit="submit" class="mt-6 space-y-6">
        <div>
            <x-input-label for="question" :value="__('Question Text')" />
            <x-text-input wire:model="form.question" id="question" class="block mt-1 w-full" type="text" name="question" required
                autofocus autocomplete="question" />
            <x-input-error :messages="$errors->get('form.question')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="answer" :value="__('Question Answer')" />
            <x-text-input wire:model="form.answer" id="answer" class="block mt-1 w-full" type="text"
                name="answer" autofocus autocomplete="answer" />
            <x-input-error :messages="$errors->get('form.answer')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-gradiant-button>{{ __('Save') }}</x-gradiant-button>

            <x-action-message class="me-3" on="question-stored">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>