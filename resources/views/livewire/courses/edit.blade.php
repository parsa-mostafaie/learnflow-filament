<?php

use function Livewire\Volt\{state, form, usesFileUploads, on, mount};
use Milwad\LaravelValidate\Rules\ValidSlug;
use Illuminate\Support\Str;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Livewire\Forms\CourseForm;
use Masmerise\Toaster\Toaster;

// Enable file uploads
usesFileUploads();

// Define the state for the component
state(['course' => null]);

// Define the form for the course
form(CourseForm::class, 'form');

// Define an event listener to handle the edit-course event
on([
    'edit-course' => function ($course_id) {
        $course = Course::withTrashed()->findOrFail($course_id);

        $this->authorize('update', $course);

        if ($this->form->setModel($course)) {
            $this->dispatch('open-modal', 'edit-course');
            $this->dispatch('course-update-form-opened');
        }
    },
]);

// Define the reset function to reset the form
$_reset = function () {
    $this->form->setModel($this->form->course);
};

// Define the submit function to handle form submission
$submit = function () {
    if (!$this->form->course) {
        return;
    }

    $this->authorize('update', $this->form->course);

    $this->form->save($this->form->course);

    $this->dispatch('close-modal', 'edit-course');
    $this->dispatch('courses-table-reload');
    $this->dispatch('course-updated');

    Toaster::success(__('Saved.'));
};
?>

{{-- Edit Course Section --}}
<div id="edit-course-section">
  <x-modal name="edit-course" focusable :show="!empty($this->form->model)">
    <div class="p-6">
      <div>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ __('Edit a course') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ __('Edit a course') }}
        </p>
      </div>

      <form wire:submit="submit" class="mt-6 space-y-6" @if (!$this->form->course) inert @endif>
        <div>
          <x-input-label for="title" :value="__('Title')" />
          <x-text-input :disabled="!$this->form->course" wire:model="form.title" id="title" class="block mt-1 w-full" type="text"
            name="title" required autofocus autocomplete="title" />
          <x-input-error :messages="$errors->get('form.title')" class="mt-2" />
        </div>

        <div>
          <x-input-label for="description" :value="__('Description')" />
          <x-text-area wire:model="form.description" id="description" class="block mt-1 w-full" type="text"
            name="description" autofocus autocomplete="description" :disabled="!$this->form->course" />
          <x-input-error :messages="$errors->get('form.description')" class="mt-2" />
        </div>

        <div>
          <x-input-label for="slug" :value="__('Slug')" />
          <x-text-input :disabled="!$this->form->course" wire:model="form.slug" id="slug" class="block mt-1 w-full" type="text"
            name="slug" autofocus autocomplete="slug" />
          <x-input-error :messages="$errors->get('form.slug')" class="mt-2" />
        </div>

        <div>
          {{-- File upload component for thumbnail --}}
          <x-file-upload wire:model="form.thumbnail" id="edit-course-dropzone-file">
            <img class="mt-2 rounded-lg block" src="{{ $this->form->tempUrl() }}" />
          </x-file-upload>
          <p class="text-purple-500 my-2" wire:loading wire:target="form.thumbnail">{{ __('Loading...') }}</p>
          <x-input-error :messages="$errors->get('form.thumbnail')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
          <x-gradient-button :disabled="!$this->form->course" wire:loading.attr="disabled"
            wire:target="form.thumbnail">{{ __('Save') }}</x-gradient-button>
          <x-gradient-button :disabled="!$this->form->course" x-on:click="$dispatch('close-modal', 'edit-course')"
            type="button">{{ __('Cancel') }}</x-gradient-button>
          <x-gradient-button type="button" wire:click="_reset">{{ __('Reset') }}</x-gradient-button>
        </div>
      </form>
    </div>
  </x-modal>
</div>
