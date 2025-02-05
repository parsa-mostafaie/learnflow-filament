@props(['question'])

<?php
use function Livewire\Volt\{computed, state, on};

use App\Models\Question;

state(['question', 'in_show']);

$question_model = computed(fn() => $this->question instanceof App\Models\Question ? $this->question : Question::findOrFail($this->question));
?>

<div>
    <x-button-group>
        @can('delete', $this->question_model)
            <livewire:questions.action.delete-button :question="$this->question_model" />
        @endcan
        @can('update', $this->question_model)
            <livewire:questions.action.edit-button :question="$this->question_model" />
        @endcan
    </x-button-group>
</div>