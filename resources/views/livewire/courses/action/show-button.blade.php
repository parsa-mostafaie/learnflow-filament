@props(['id'])

<?php
use function Livewire\Volt\{state, computed};
use App\Models\Course;

// Define the state for the component
state(['course']);
?>

{{-- Container for the show button --}}
<div>
  @can('view', $this->course)
  <x-secondary-button wire:navigate
    href="{{ route('course.single', $this->course->slug) }}" title="{{ __('actions.view') }}">
    <x-heroicon-s-eye class="w-4 h-4" />
  </x-secondary-button>
  @endcan
</div>
