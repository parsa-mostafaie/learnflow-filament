<?php

use function Livewire\Volt\{state, form, usesFileUploads};
use Milwad\LaravelValidate\Rules\ValidSlug;
use Illuminate\Support\Str;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\CourseForm;

// Enable file uploads
usesFileUploads();

// Define the form for the course
form(CourseForm::class);

// Define the submit function to handle form submission
$submit = function () {
    $this->authorize('create', Course::class);

    $this->form->save();

    $this->dispatch('course-stored');
    $this->dispatch('courses-table-reload');
};

// Define the reset function to reset the form
$_reset = function () {
    $this->form->reset();
};
?>

<section class="m-2 mx-3">
  <div>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
      {{ __('Add a course') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
      {{ __('Create a new course') }}
    </p>
  </div>

  <form wire:submit="submit" class="mt-6 space-y-6">
    <div>
      <x-input-label for="title" :value="__('Title')" />
      <x-text-input wire:model="form.title" id="title" class="block mt-1 w-full" type="text" name="title" required
        autofocus autocomplete="title" />
      <x-input-error :messages="$errors->get('form.title')" class="mt-2" />
    </div>
    <div>
      <x-input-label for="description" :value="__('Description')" />
      <x-text-input wire:model="form.description" id="description" class="block mt-1 w-full" type="text"
        name="description" autofocus autocomplete="description" />
      <x-input-error :messages="$errors->get('form.description')" class="mt-2" />
    </div>
    <div>
      <x-input-label for="slug" :value="__('Slug')" />
      <x-text-input wire:model="form.slug" id="slug" class="block mt-1 w-full" type="text" name="slug"
        autofocus autocomplete="slug" />
      <x-input-error :messages="$errors->get('form.slug')" class="mt-2" />
    </div>

    <div>
      {{-- File upload component for thumbnail --}}
      <x-file-upload wire:model="form.thumbnail" id="create-course-dropzone-file">
        @if ($this->form->tempUrl())
          <img class="mt-2 rounded-lg block" src="{{ $this->form->tempUrl() }}" />
        @endif
      </x-file-upload>

      <p class="text-purple-500 my-2" wire:loading wire:target="form.thumbnail">{{ __('Loading...') }}</p>

      <x-input-error :messages="$errors->get('form.thumbnail')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
      <x-gradient-button>{{ __('Save') }}</x-gradient-button>
      <x-gradient-button type="button" wire:click="_reset">{{ __('Reset') }}</x-gradient-button>

      <x-action-message class="me-3" on="course-stored" message="{{ __('Saved.') }}">
        {{ __('Saved.') }}
      </x-action-message>
    </div>
  </form>
</section>
