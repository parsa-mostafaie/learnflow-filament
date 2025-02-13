<?php
use function Livewire\Volt\{state, form, usesFileUploads, on, computed, usesPagination, mount};

use Milwad\LaravelValidate\Rules\ValidSlug;
use Illuminate\Support\Str;
use App\Models\Question, App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Livewire\Forms\CourseForm;

usesFileUploads();
usesPagination();

state(['course' => null, 'selectedQuestions' => [], 'search' => null, 'limit' => 10, 'more_found' => true, 'offset' => 0, 'questions' => []]);

form(CourseForm::class, 'form');

// Event listener to open the assign course modal and load selected questions
on([
    'assign-to-course' => function ($course_id) {
        $course = Course::withTrashed()->findOrFail($course_id);

        $this->authorize('assign', $course);

        if ($this->form->setModel($course)) {
            $this->selectedQuestions = $course->questions->pluck('id')->toArray();
            $this->dispatch('open-modal', 'assign-course');
            $this->dispatch('course-assign-form-opened');
        }
    },
]);

// Load questions based on search and pagination
$loadQuestions = function () {
    $newQuestions = Question::search($this->search)->limit($this->limit)
        ->offset($this->offset)
        ->get();

    $this->questions = array_merge($this->questions, $newQuestions->toArray());

    if (count($newQuestions) < $this->limit) {
        $this->more_found = false;
    }
};

// Load more questions for pagination
$loadMore = function () {
    $this->offset += $this->limit;
    $this->loadQuestions();
};

// Submit the selected questions to the course
$submit = function () {
    if (!$this->form->course) {
        return;
    }

    $this->authorize('assign', $this->form->course);

    $this->form->sync($this->selectedQuestions);

    $this->dispatch('close-modal', 'assign-course');
    $this->dispatch('courses-table-reload');
    $this->dispatch('course-assigned');
};

// Handle search input change and reload questions
$searchChange = function () {
    $this->more_found = true;
    $this->offset = 0;
    $this->questions = [];
    $this->loadQuestions();
};

// Initial mount to load questions
mount(fn() => $this->loadQuestions());
?>

<div id="assign-course-section">
  <x-modal name="assign-course" focusable :show="!empty($this->form->model)">
    <div class="p-6">
      <div>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ __('Assign a question to course') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ __('Assign a question to course') }}
        </p>
      </div>

      <div class="my-4">
        <x-text-input type="text" wire:model.live="search" placeholder="{{ __('Search questions...') }}"
          wire:input="searchChange()" />
      </div>

      <div class="relative bg-dark shadow rounded-lg mb-4">
        <ul class="divide-y divide-gray-200">
          @foreach ($this->questions as $question)
            <li class="px-4 py-4 flex items-center">
              <input type="checkbox" id="ci_question_{{ $question['id'] }}" wire:key="ci_question_{{ $question['id'] }}" wire:model="selectedQuestions"
                value="{{ $question['id'] }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded me-3"
                @if (in_array($question['id'], $selectedQuestions)) checked @endif>
              <span class="text-gray-900 dark:text-gray-300" style="direction:auto;">{{ $question['question'] }}:
                {{ $question['answer'] }}</span>
            </li>
          @endforeach
        </ul>

        <div class="text-center p-4 text-white absolute right-[50%] top-[50%] shadow-xl rounded-lg bg-purple-600"
          wire:loading style="transform: translate(50%, -50%);">
          {{ __('Loading...') }}
        </div>

        @if ($more_found)
          <div class="px-4 py-3 bg-dark-50 text-right sm:px-6">
            <x-gradient-button type="button" wire:click="loadMore">{{ __('Load More') }}</x-gradient-button>
          </div>
        @endif
      </div>

      <x-secondary-button wire:click="submit">
        {{ __('Assign Questions') }}
      </x-secondary-button>
    </div>
  </x-modal>
</div>
