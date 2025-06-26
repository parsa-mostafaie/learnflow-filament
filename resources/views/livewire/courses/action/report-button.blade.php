@props(['id'])

<?php
use function Livewire\Volt\state;

// Define the state for the component
state(['course']);
?>

<div>
  @can('getReport', $this->course)
    <x-secondary-button wire:navigate href="{{ route('course.report', $this->course->id) }}" title="{{ __('report-page.action') }}">
      <x-heroicon-s-chart-bar class="w-4 h-4" />
    </x-secondary-button>
  @endcan
</div>
