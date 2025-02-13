@props(['id'])

<?php
use function Livewire\Volt\{state, computed};
use App\Models\Course;

// Define the state for the component
state(['course']);
?>

{{-- Container for the show button --}}
<div>
  {{-- Secondary button with navigation --}}
  <x-secondary-button wire:navigate
    href="{{ route('course.single', $this->course->slug) }}">{{ __('View') }}</x-secondary-button>
</div>
