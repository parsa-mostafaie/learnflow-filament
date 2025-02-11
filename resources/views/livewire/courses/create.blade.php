<?php

use function Livewire\Volt\{state, form, usesFileUploads};

use Milwad\LaravelValidate\Rules\ValidSlug;
use Illuminate\Support\Str;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\CourseForm;

usesFileUploads();

form(CourseForm::class);

$submit = function () {
    $this->authorize('create', Course::class);

    $this->form->save();

    $this->dispatch('course-stored');
    $this->dispatch('courses-table-reload');
};

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
      {{-- <input type="file" wire:model="form.thumbnail" class="ring-none"> --}}

      <x-file-upload wire:model="form.thumbnail" id="create-course-dropzone-file">
        @if ($this->form->thumbnail)
          <img class="mt-2 rounded-lg w-[50%] block" src="{{ $this->form->thumbnail->temporaryUrl() }}" />
        @endif
      </x-file-upload>

      <x-input-error :messages="$errors->get('form.thumbnail')" class="mt-2" />

    </div>

    <div class="flex items-center gap-4">
      <x-gradient-button>{{ __('Save') }}</x-gradient-button>
      <x-gradient-button type="button" wire:click="_reset">{{ __('Reset') }}</x-gradient-button>

      <x-action-message class="me-3" on="course-stored">
        {{ __('Saved.') }}
      </x-action-message>
    </div>
  </form>
</section>
