@props(['id'])

<?php
use function Livewire\Volt\{state, computed};
use App\Models\Course;

state(['course']);

?>

<div>
    <x-secondary-button wire:navigate href="{{ route('course.single', $this->course->slug) }}">View</x-secondary-button>
</div>