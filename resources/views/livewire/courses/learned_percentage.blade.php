@props(['course_user'])
<?php
use App\Facades\Leitner;
use function Livewire\Volt\{state, computed};
use App\Models\CourseUser;

state(['course_user']);

$course_user_model = computed(fn() => CourseUser::findOrFail($this->course_user));
?>
<div>
  <x-progress :percentage="Leitner::getLearnedPercentage($this->course_user_model->course, $this->course_user_model->user)"></x-progress>
</div>
