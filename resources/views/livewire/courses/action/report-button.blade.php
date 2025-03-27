@props(['id'])

<?php
use function Livewire\Volt\state;

// Define the state for the component
state(['course']);
?>

<div>
  @can('getReport', $this->course)
    <x-secondary-button wire:navigate href="{{ route('course.report', $this->course->id) }}" title="{{ __('Get Report') }}">
      <i class="fas fa-chart-line"></i>
    </x-secondary-button>
  @endcan
</div>
