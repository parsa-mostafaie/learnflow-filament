<?php
use function Livewire\Volt\{state, form, usesFileUploads, on, computed, usesPagination, mount};

use Milwad\LaravelValidate\Rules\ValidSlug;
use Illuminate\Support\Str;
use App\Models\Question, App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Livewire\Forms\MultipleQuestionAssignForm;
use Masmerise\Toaster\Toaster;

usesFileUploads();

state(['course' => null]);

form(MultipleQuestionAssignForm::class, 'form');

on([
    'multiple-assign-to-course' => function ($course_id) {
        $course = Course::withTrashed()->findOrFail($course_id);

        $this->authorize('assignMany', $course);

        if ($this->form->setModel($course)) {
            $this->selectedQuestions = $course->questions->pluck('id')->toArray();
            $this->dispatch('open-modal', 'multiple-assign-course');
            $this->dispatch('course-multiple-assign-form-opened');
        }
    },
]);

// Submit the selected filr to the course
$submit = function () {
    if (!$this->form->course) {
        return;
    }

    $this->authorize('assignMany', $this->form->course);

    $this->form->save();

    $this->dispatch('close-modal', 'multiple-assign-course');
    $this->dispatch('courses-table-reload');
    $this->dispatch('course-multiple-assigned');
    Toaster::info(__('Saved.'));
};

?>

<div id="multiple-assign-course-section">
  <x-modal name="multiple-assign-course" focusable :show="!empty($this->form->model)">
    <div class="p-6">
      <div>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ __('Assign questions to course') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ __('Assign questions to course') }}
        </p>
      </div>

      <form wire:submit="submit" class="space-y-6" @if (!$this->form->course) inert @endif>
        <div>
          <x-file-upload wire:model="form.file" id="multiple-assign-dropzone-file" accept=".xlsx, .xls, .csv">
            <x-slot name="file_types">{{ __('Xlsx, Xls, CSV documents only') }}</x-slot>
          </x-file-upload>
          <p class="text-purple-500 mb-4 -mt-1" wire:loading>{{ __('Loading...') }}</p>
          <x-input-error :messages="$errors->get('form.file')" class="mb-3" />
        </div>
      </form>

      <x-secondary-button wire:click="submit">
        {{ __('Assign Multiple') }}
      </x-secondary-button>
    </div>
  </x-modal>
</div>
