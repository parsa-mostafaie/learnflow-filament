<?php
use function Livewire\Volt\{state, form, usesFileUploads, on, usesPagination, mount};
use App\Models\{Question, Course};
use App\Livewire\Forms\CourseForm;
use Masmerise\Toaster\Toaster;

usesFileUploads();
usesPagination();

state([
    'course' => null,
    'selectedQuestions' => [],
    'search' => [
        'text' => null,
        'filters' => [
            'status' => null,
            'only_my_questions' => false,
            'assignment' => '', // Dropdown: '' = All, 'assigned' = Only Assigned, 'unassigned' = Only Unassigned.
        ],
        'sortBy' => 'questions.created_at',
        'sortDirection' => 'desc',
    ],
    'limit' => 10,
    'offset' => 0,
    'questions' => [],
    'more_found' => true,
]);
form(CourseForm::class, 'form');

on([
    'assign-to-course' => function ($course_id) {
        $course = Course::withTrashed()->findOrFail($course_id);
        $this->authorize('assign', $course);
        if ($this->form->setModel($course)) {
            $this->selectedQuestions = $course->questions_all()->pluck('questions.id')->toArray();
            $this->dispatch('open-modal', 'assign-course');
            $this->dispatch('course-assign-form-opened');
        }
    },
]);

$loadQuestions = function () {
    // Merge current course id into search data.
    $searchData = array_merge($this->search, [
        'course_id' => $this->form->course ? $this->form->course->id : null,
    ]);
    $newQuestions = Question::search($searchData)->limit($this->limit)->offset($this->offset)->get();
    $this->questions = array_merge($this->questions, $newQuestions->toArray());
    $this->more_found = count($newQuestions) === $this->limit;
};

$loadMore = function () {
    $this->offset += $this->limit;
    $this->loadQuestions();
};

$submit = function () {
    if (!$this->form->course) {
        return;
    }
    $this->authorize('assign', $this->form->course);
    $this->form->sync($this->selectedQuestions);
    $this->dispatch('close-modal', 'assign-course');
    $this->dispatch('courses-table-reload');
    $this->dispatch('course-assigned');
    Toaster::info(__('Saved.'));
};

$searchChange = function () {
    $this->offset = 0;
    $this->questions = [];
    $this->more_found = true;
    $this->loadQuestions();
};

mount(fn() => $this->loadQuestions());
?>

<div id="assign-course-section">
  <x-modal name="assign-course" focusable :show="!empty($this->form->model)">
    <!-- Flex column container -->
    <div class="flex flex-col h-full">

      <!-- Sticky Header: Search, Filters & Sorting -->
      <div class="sticky top-0 z-20 bg-white dark:bg-gray-800 p-6 space-y-6 shadow">
        <div>
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Assign Questions to a Course') }}
          </h2>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Search, filter, and sort questions to assign to this course.') }}
          </p>
        </div>
        <x-search-input model="search.text" placeholder="{{ __('Search questions...') }}" onInput="searchChange()" />
        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow">
          <p class="font-bold text-gray-800 dark:text-gray-200 mb-2">{{ __('Filters') }}</p>
          <div class="flex flex-wrap gap-4 items-center">
            <x-dropdown-filter id="status-filter" model="search.filters.status" label="{{ __('Status') }}"
              :options="[
                  '' => __('All'),
                  'approved' => __('approved'),
                  'rejected' => __('rejected'),
                  'pending' => __('pending'),
              ]" />
            <x-checkbox-filter id="ownership-filter" model="search.filters.only_my_questions"
              label="{{ __('Only My Questions') }}" />
            <x-dropdown-filter id="assignment-filter" model="search.filters.assignment" label="{{ __('Assignment') }}"
              :options="[
                  '' => __('All'),
                  'assigned' => __('Only Assigned'),
                  'unassigned' => __('Only Unassigned'),
              ]" />
          </div>
        </div>
        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow">
          <p class="font-bold text-gray-800 dark:text-gray-200 mb-2">{{ __('Sort By') }}</p>
          <div class="flex flex-wrap gap-4 items-center">
            <x-dropdown-filter id="sort-column" model="search.sortBy" label="{{ __('Column') }}" :options="[
                'questions.created_at' => __('Creation Date'),
                'questions.status' => __('Status'),
                'questions.question' => __('Question'),
            ]" />
            <x-dropdown-filter id="sort-direction" model="search.sortDirection" label="{{ __('Direction') }}"
              :options="[
                  'asc' => __('Ascending'),
                  'desc' => __('Descending'),
              ]" />
          </div>
        </div>
      </div>

      <!-- Scrollable Questions List Container -->
      <div class="relative p-6 overflow-y-auto max-h-80">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
          @if (count($this->questions) > 0)
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
              @foreach ($this->questions as $question)
                <x-question-item :question="$question" selected="selectedQuestions" />
              @endforeach
            </ul>
          @else
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
              {{ __('No results found') }}
            </div>
          @endif
        </div>
      </div>

      <!-- Sticky Footer: Load More and Action Buttons -->
      <div class="sticky bottom-0 z-20 bg-white dark:bg-gray-800 p-6 space-y-4 shadow">
        @if ($more_found)
          <div class="text-right">
            <x-gradient-button type="button" wire:click="loadMore">
              {{ __('Load More') }}
            </x-gradient-button>
          </div>
        @endif
        <div class="text-right space-x-2">
          <x-secondary-button wire:click="submit">
            {{ __('Assign Questions') }}
          </x-secondary-button>
          <x-secondary-button wire:click="$dispatch('close-modal', 'assign-course')">
            {{ __('Cancel') }}
          </x-secondary-button>
        </div>
      </div>
    </div>
  </x-modal>

  <div wire:loading class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"> <x-loader /> </div>
  </div>
</div>
